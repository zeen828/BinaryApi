<?php

namespace App\Console\Commands\Binary;

use Illuminate\Console\Command;
// 模型
use App\Models\Binary\BinaryCurrencyTrend;
use App\Models\User\UserBetting;
// 開發
use App\Libraries\Binary\Third\NomicsApi;
use App\Libraries\Binary\BinaryDraw;
// Redis
use Illuminate\Support\Facades\Redis;
// 時間
use Carbon\Carbon;
// Swoole
Use Co;

class GetTrendDrawJob extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan binary:getTrendDraw
     * 
     * @var string
     */
    protected $signature = 'binary:getTrendDraw';

    /**
     * The console command description.
     * 透過nomics的API來取得當下走勢資料更新資料庫[二元期權資料 - 幣種 - 走勢]資料.
     *
     * @var string
     */
    protected $description = '[Binary]';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 開始消息
        $this->line('[' . date('Y-m-d H:i:s') . '] START');

        // 1.透過API取得資料整理API幣種走勢
        // NomicsApi紀錄進Redis，避免失敗
        $keyNomicsApi = sprintf('Binary:%s', 'NomicsApi');
        // API幣種走勢暫存(如果API打不到資料就靠它了)
        $currency = json_decode(Redis::get($keyNomicsApi), true);
        if(empty($currency) || !is_array($currency))
        {
            $currency = array();
        }
        // API函式
        $api = new NomicsApi();
        $apiData = $api->getTicker('', '');
        // 有資料；反解JSON做整理，記錄到REDIS備用
        if(!empty($apiData))
        {
            $apiData = json_decode($apiData, true);
            if(is_array($apiData))
            {
                // print_r($apiData);
                foreach ($apiData as $key=>$val)
                {
                    // print_r($val);
                    $currency[$val['currency']] = $val['price'];
                    unset($val);
                }
                // print_r($currency);
                // NomicsApi紀錄進Redis，避免失敗
                Redis::set($keyNomicsApi, json_encode($currency));
            }
        }
        unset($apiData, $keyNomicsApi);

        // 2.時間處理
        $now = Carbon::now()->setTimezone(config('app.timezone'));
        // 現在時間+1分鐘
        $start = Carbon::parse($now)->setTimezone(config('app.timezone'))->addMinutes(1);

        $startAt = $start->format('Y-m-d H:i:00');
        $endAt = $start->format('Y-m-d H:i:59');// 00會開道下一期的
        // echo 'start：', $startAt, ' end：', $endAt;

        // 3.要開獎走勢期數獲取
        $trends = BinaryCurrencyTrend::where('status', '0')->whereBetween('draw_at', array($startAt, $endAt))->get();
        // print_r($trends);
        if($trends->isEmpty()) {
            $this->error('[' . date('Y-m-d H:i:s') . '] Not Found！');
            return false;
        }

        // swoole多執行序
        Co\run(function() use ($currency, $trends) {
            foreach($trends as $trend)
            {
                // swoole多執行序
                go(function() use ($currency, $trend) {
                    // print_r($trend);
                    // 4.開獎LIB-直接帶入Model
                    $libDraw = new BinaryDraw($trend);
                    // 輸入API取得走勢
                    $libDraw->setTrendApi($currency[$trend->rCurrency->binary_code]);
                    $trend->trend_api = $currency[$trend->rCurrency->binary_code];
                    // 運作
                    $libDraw->run();
                    // 設定走勢用資料
                    $libDraw->setTrendCycle($trend->bet_at, $trend->draw_at, 1, 0.06);
                    // 走勢
                    $libDraw->forecast();
                    // 中獎規則
                    $libDraw->drawRule();
                    // 取開獎號碼
                    $tmpDraw = $libDraw->get();
                    // echo '取開獎號碼：', $tmpDraw, "<br/>\n";
                    // 取走勢
                    $tmpTrend = $libDraw->getTrend();
                    // echo '取走勢：', $tmpTrend, "<br/>\n";
                    // 取走勢資料
                    $tmpForecast = $libDraw->getForecast();
                    // print_r(json_decode($tmpForecast, true));
                    // 取中獎規則資料
                    $tmpDrawRule = $libDraw->getDrawRule();
                    // 5.紀錄進Redis
                    $keyForecast = sprintf('Binary:Currency:%s:Forecast', $trend->binary_currency_id);
                    Redis::set($keyForecast, $tmpForecast);
                    // 6.取上一期走勢，紀錄上一級走勢，系統兌獎
                    $trendBefore = BinaryCurrencyTrend::where('binary_currency_id', $trend->binary_currency_id)
                        ->where('id', '<', $trend->id)
                        ->orderBy('id', 'desc')
                        ->first();
                    // echo '測試資料：CID：', $trend->binary_currency_id, '：ID:', $trendBefore->id, '-', $trend->id;
                    if (!empty($trendBefore->draw_rule_json))
                    {
                        $tmpDraw_rule = json_decode($trendBefore->draw_rule_json, true);
                        UserBetting::where('binary_currency_trend_id', $trendBefore->id)
                            ->whereIn('binary_rule_currency_value', $tmpDraw_rule)
                            ->update(array('win_sys' => 1));
                        UserBetting::where('binary_currency_trend_id', $trendBefore->id)
                            ->whereNotIn('binary_rule_currency_value', $tmpDraw_rule)
                             ->update(array('win_sys' => -1, 'win_user' => -1));
                    } else {

                    }
                    $trendBefore->redeem = 1;
                    $trendBefore->save();
                    // 7.走勢更新資料
                    $trend->draw = $tmpDraw;
                    $trend->trend_before = $trendBefore->trend;
                    $trend->trend = $tmpTrend;
                    $trend->forecast = $tmpForecast;
                    $trend->draw_rule_json = $tmpDrawRule;
                    $trend->status = 1;
                    $trend->save();
                    // echo '取走勢資料：', $tmpForecast, "<br/>\n";
                    $this->comment('[' . date('Y-m-d H:i:s') . '] Have Data! Update Binary Currency Trend ' . $trend->id . ' Data!');
                    unset($tmpForecast, $tmpTrend, $tmpDraw, $libDraw, $trend);
                });
            }
        });

        // 結束消息
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
