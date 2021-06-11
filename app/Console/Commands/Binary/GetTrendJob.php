<?php

namespace App\Console\Commands\Binary;

use Illuminate\Console\Command;
// 模型
use App\Models\Binary\BinaryCurrency;
use App\Models\Binary\BinaryCurrencyTrend;
// 時間
use Carbon\Carbon;
// Swoole
Use Co;

class GetTrendJob extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan binary:getTrend --period=today
     * 
     * @var string
     */
    protected $signature = 'binary:getTrend {--period= : 運行周期[yesterday昨天, today今天, tomorrow明天(預設)]}';

    /**
     * The console command description.
     * 整理乙存資料庫[二元期權資料 - 幣種]資料預先處理走勢週期來建立/更新資料庫[二元期權資料 - 幣種 - 走勢]資料.
     *
     * @var string
     */
    protected $description = '[Binary] Finishing cycle create "binary currency trend DB" by "binary currency DB".';

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

        $period = $this->option('period');
        $this->info('[' . date('Y-m-d H:i:s') . '] Option input period：' . $period);
        // 選項走勢
        switch ($period)
        {
            case 'yesterday':
                // 昨天
                $baseCarbon = Carbon::yesterday();
                break;
            case 'today':
                // 今天
                $baseCarbon = Carbon::today();
                break;
            default:
                // 明天
                $baseCarbon = Carbon::tomorrow();
                break;
        }

        // 輸出星期
        $week = ($baseCarbon->dayOfWeek == 0)? '7' : (string) $baseCarbon->dayOfWeek;
        // 輸出日期字串
        $baseDay = $baseCarbon->toDateString();
        $this->info('[' . date('Y-m-d H:i:s') . '] baseCarbon： ' . $baseCarbon . ', Week：' . $week . ', baseDay：' . $baseDay);

        // 取資料庫資料(二元期權 - 幣種)
        $binaryCurrency = BinaryCurrency::whereJsonContains('week', [$week])->where('status', '1')->get();
        if($binaryCurrency->isEmpty()) {
            $this->error('[' . date('Y-m-d H:i:s') . '] Not Found！');
            return false;
        }

        // swoole多執行序
        Co\run(function() use ($baseDay, $binaryCurrency) {
            // 開獎用
            // $libDraw = new BinaryDraw();
            foreach($binaryCurrency as $currency)
            {
                // swoole多執行序
                go(function() use ($baseDay, $currency) {
                    // 開獎用 - 設定資料
                    // $libDraw->setBaseDara($currency->trend_data_json);
                    // print_r($currency);
                    // 開始時間
                    $startDate = Carbon::parse($baseDay . $currency->start_t);
                    // 結束時間
                    if ($currency->end_t == '23:59:59') {
                        // 23:59:59代表跨日做處理
                        $endDate = Carbon::parse($baseDay)->addDay();
                    } else {
                        $endDate = Carbon::parse($baseDay . $currency->end_t);
                    }
                    // 工作秒數
                    $workSeconds = $endDate->timestamp - $startDate->timestamp;
                    // 開講次數
                    $count = floor($workSeconds / $currency->repeat);
                    // 開獎時間
                    $drawDate = Carbon::parse($baseDay . $currency->start_t)->addSeconds($currency->repeat);
                    // echo 'BinaryCode：', $currency->binary_code, ', CurrencyCode：', $currency->currency_code, "\n";
                    // echo 'startDate：', $startDate, ', endDate：', $endDate, ', workSeconds：', $workSeconds, "\n";
                    // echo 'count：', $count, ', drawDate：', $drawDate, "\n";

                    $this->info('[' . date('Y-m-d H:i:s') . '] Binary ' . $currency->binary_code . ' Currency ' . $currency->currency_code . ' Running!');
                    // $count = 4;
                    for ($i = 0;$i < $count;$i++)
                    {
                        // 開獎用 - 設定資料 - 開獎
                        // $draw = $libDraw->randDraw();
                        // $draw = $libDraw->run()->getTrend();
                        // 期別
                        $period = $currency->binary_code . $currency->currency_code . $drawDate->format('YmdHi');
                        // 開始投注時間(開獎時間-間格秒數)
                        $startDate = Carbon::parse($drawDate)->subSeconds($currency->repeat);
                        // 停止投注時間(開獎時間-截止投注時間)
                        $stopDate = Carbon::parse($drawDate)->subSeconds($currency->stop_enter);
                        // 檢查重複(二元期權 - 幣種 - 走勢)
                        $binaryCurrencyTrend = BinaryCurrencyTrend::where('binary_currency_id', $currency->id)->where('draw_at', $drawDate)->first();
                        if(empty($binaryCurrencyTrend))
                        {
                            // 不存在(二元期權 - 幣種 - 走勢)
                            $this->info('[' . date('Y-m-d H:i:s') . '] ' . $currency->id . 'No Data! Create Binary Currency Trend Data!');
                            BinaryCurrencyTrend::create([
                                'binary_currency_id' => $currency->id,
                                'period' => $period,
                                // 'draw' => $draw->get(),
                                // 'draw_rule_json' => $draw->rule(),
                                'draw_at' => $drawDate,
                                'bet_at' => $startDate,// 開始投注時間
                                'stop_at' => $stopDate,// 停止投注時間
                            ]);
                        } else {
                            // 存在(二元期權 - 幣種 - 走勢)
                            $this->comment('[' . date('Y-m-d H:i:s') . '] ' . $currency->id . 'Have Data! Update Binary Currency Trend Data!');
                            $binaryCurrencyTrend->binary_currency_id = $currency->id;
                            $binaryCurrencyTrend->period = $period;
                            // $binaryCurrencyTrend->draw = $draw->get();
                            // $binaryCurrencyTrend->draw_rule_json = $draw->rule();
                            $binaryCurrencyTrend->draw_at = $drawDate;
                            $binaryCurrencyTrend->bet_at = $startDate;
                            $binaryCurrencyTrend->stop_at = $stopDate;
                            $binaryCurrencyTrend->save();
                        }
                        // 下一期走勢時間
                        $drawDate = $drawDate->addSeconds($currency->repeat);
                    }
                });
            }
        });

        // 結束消息
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
