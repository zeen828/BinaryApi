<?php

namespace App\Console\Commands\Tests;

use Illuminate\Console\Command;
// Redis
use Redis;
// 時間
use Carbon\Carbon;

class loopJob extends Command
{
    private $showCount = 20;
    /**
     * The name and signature of the console command.
     * 
     * php artisan tests:loopJob
     * 
     * @var string
     */
    protected $signature = 'tests:loopJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Tests]';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->showCount = env('CHART_SHOW_COUNT', 20);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 開始消息
        // $timeZone = 'Asia/Taipei';
        $this->line('[' . date('Y-m-d H:i:s') . '] START');
        $domain = '127.0.0.1';
        $channelName = "private-channel-name";
        $nowAt = Carbon::now()->setTimezone(config('app.timezone'));
        $point = 1000.00;
        $range = 0.06;
        $redis = new Redis();
        $redis->connect($domain, 6379);
        $data = [
            'currency' => 1,
            'forecast' => [],
            'area' => '',
        ];
        // for ($i=1;$i<=60;$i++) {
        for ($i=1;$i>=0;$i++) {
            $this->line('run：' . $i);
            // 顯示筆數限制
            if (count($data['forecast']) >= $this->showCount) {
                array_shift($data['forecast']);
            }
            // 時間
            $nowAt->addSeconds(1);
            // 點數
            $pointRange = (($point * $range) <= 1)? 1.1 : $point * $range;
            $this->line('pointRange' . $pointRange);

            $pointRangeEnd = (empty($pointRange))? 0 : -1 * $pointRange;
            $point = $point + rand($pointRange * 100, $pointRangeEnd * 100) / 100;
            // 組合資料
            $data['forecast'][] = [
                'name' => $nowAt->format('H:i:s'),
                'value' => [
                    $nowAt->toDateTimeString(),
                    $point,
                ]
            ];
            // $data[] = array_shift($tmp);
            $redis->publish($channelName, json_encode($data));
            sleep(1);
        }
        // 結束消息
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
