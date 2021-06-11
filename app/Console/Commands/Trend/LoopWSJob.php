<?php

namespace App\Console\Commands\Trend;

use Illuminate\Console\Command;
// 模型
use App\Models\Binary\BinaryCurrency;
use App\Models\Binary\BinaryCurrencyTrend;
// 開發
use App\Libraries\Binary\BinaryDraw;
// Redis
use Redis as NewRedis;
use Illuminate\Support\Facades\Redis;
// 時間
use Carbon\Carbon;
// Swoole
Use Co;

use function GuzzleHttp\json_encode;

class LoopWSJob extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan trend:loopWS
     * 
     * @var string
     */
    protected $signature = 'trend:loopWS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Binary-Trend]抓走勢資料餵給Redis的頻道';

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
        $delaySec = env('LOOP_DELAY_SEC', 3);
        sleep($delaySec);
        $this->line('[' . date('Y-m-d H:i:s') . '] Sleep ' . $delaySec . 'Sec');

        // $currencyId = (int) $this->option('currency');
        // if(empty($currencyId)){
        //     $currencyId = 1;
        // }
        // echo 'Id：', $currencyId, "\n";

        // 取資料庫資料(二元期權 - 幣種)
        $binaryCurrency = BinaryCurrency::where('status', '1')->get();
        if($binaryCurrency->isEmpty()) {
            $this->error('[' . date('Y-m-d H:i:s') . '] Not Found！');
            return false;
        }

        // NewRedis
        $domain = '127.0.0.1';
        $redisNew = new NewRedis();
        $redisNew->connect($domain, 6379);
        // PS：這邊用原生REDIS，因為Laravel頻道還沒研究好，

        // swoole多執行序
        Co\run(function() use ($redisNew, $binaryCurrency) {
            foreach($binaryCurrency as $currency)
            {
                // swoole多執行序
                go(function() use ($redisNew, $currency) {
                    $currencyId = $currency->id;
                    // echo 'Id：', $currencyId, "\n";

                    // 1.取走勢用資料(20筆)
                    $keyTrend = sprintf('binary_database_Trend:Channel:%s', $currencyId);
                    // echo 'keyTrend：', $keyTrend, "\n";
                    $jsonTrend = $redisNew->get($keyTrend);
                    // print_r($jsonTrend);
                    $dataTrend = json_decode($jsonTrend, true);
                    if(empty($dataTrend) || empty($dataTrend['endAt'])){
                        // 整理走勢基礎資料
                        $nowAt = Carbon::now()->setTimezone(config('app.timezone'));
                        $dataTrend = [
                            'endAt' => $nowAt->format('Y-m-d H:i:s'),
                            // 'endAt' => '2020-01-01 00:00:01',
                            'currency' => $currencyId,
                            'forecast' => [],
                            'area' => [],
                        ];
                    }else{
                        // Redis裡的資料改幣種ID
                        $dataTrend['currency'] = $currencyId;
                    }
                    // print_r($dataTrend);

                    // binary_database_Binary:Currency:5:Forecast
                    // 取幣種走勢資料
                    $keyForecast = sprintf('binary_database_Binary:Currency:%s:Forecast', $currencyId);
                    // echo 'keyForecast：', $keyForecast, "\n";
                    $jsonForecast = $redisNew->get($keyForecast);
                    // print_r($jsonForecast);
                    $dataForecast = json_decode($jsonForecast, true);
                    // print_r($dataForecast);

                    // 沒值就放棄
                    if(empty($dataForecast)){
                        // echo '沒有值';
                        return false;
                    } else {
                        // echo '有值';
                    }

                    // 停止投注區塊
                    if(!is_array($dataTrend['area'])){
                        $dataTrend['area'] = [];
                    }
                    // 判斷陣列筆數
                    if(count($dataTrend['area']) >= 3){
                        // 刪除第一筆
                        array_shift($dataTrend['area']);
                    }
                    $dataTrend['area'][] = [
                        ['xAxis' => $dataForecast['49']['value']['0']],
                        ['xAxis' => $dataForecast['59']['value']['0']],
                    ];

                    // 迴圈
                    foreach($dataForecast as $key => $value)
                    {
                        // print_r($value);
                        $endAt = Carbon::parse($dataTrend['endAt'])->setTimezone(config('app.timezone'));
                        $valAt = Carbon::parse($value['value']['0'])->setTimezone(config('app.timezone'));
                        // echo 'Id：', $currencyId,'，endAt：', $endAt->toDateTimeString(), '，valAt：', $valAt->toDateTimeString(), "\n";
                        // print_r($valAt);
                        if($endAt->lt($valAt))
                        {
                            // echo 'O';
                            // 判斷陣列筆數
                            if(count($dataTrend['forecast']) >= 20){
                                // 刪除第一筆
                                array_shift($dataTrend['forecast']);
                            }
                            // 加入陣列
                            $dataTrend['endAt'] = $value['value']['0'];
                            $dataTrend['forecast'][] = $value;
                            // print_r($dataTrend);
                            
                            $channelName = "private-channel-name";
                            $redisNew->publish($channelName, json_encode($dataTrend));
                            $redisNew->set($keyTrend, json_encode($dataTrend));
                            // 延遲1秒
                            sleep(1);
                        }else{
                            // echo 'X';
                        }
                    }
                });
            }
        });

        // 結束消息
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
