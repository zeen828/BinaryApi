<?php

namespace App\Console\Commands\Trend;

use Illuminate\Console\Command;
// 模型
use Illuminate\Support\Facades\DB;
use App\Models\User\User;
use App\Models\User\UserBetting;
use App\Models\Order\Order;
// 時間
use Carbon\Carbon;
// Swoole
Use Co;

class MatchAwardsJob extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan trend:matchAwards
     * 
     * @var string
     */
    protected $signature = 'trend:matchAwards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Binary-Trend]開獎後配獎給用戶';

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

        // 1.剩餘配彩筆數
        $count = UserBetting::where('win_sys', '1')->where('win_user', '0')->count();
        $this->comment('[' . date('Y-m-d H:i:s') . '] Need to prepare the number of matches['. $count .']');
        // print_r($count);
        // 2.有未兌獎的執行迴圈
        while($count > 0){
            // 3.最小一筆已開獎未對獎的資料
            $betting = UserBetting::where('win_sys', '1')->where('win_user', '0')->take(500)->get();
            // print_r($trends);
            foreach($betting as $bet)
            {
                DB::beginTransaction();
                // 4.取該期中獎者更新(待開獎>>中獎)增加點數(User點數)
                $addPoint = $bet->amount + $bet->profit;
                User::where('id', $bet->user_id)->where('status', 1)->increment('point', $addPoint);
                // 得分紀錄
                Order::create([
                    'sn' => sprintf('WIN-%012d', $bet->id),
                    'user_id' => $bet->user_id,
                    'event' => 'score',
                    'point' => $addPoint,
                    'remarks' => '得分',
                    'status' => '1',
                ]);
                $bet->win_user = 1;
                $bet->save();
                DB::commit();
            }
            // 7.剩餘配彩筆數
            $count = UserBetting::where('win_sys', '1')->where('win_user', '0')->count();
            $count = 0;
            // print_r($count);
        }

        // 結束消息
        $this->line('[' . date('Y-m-d H:i:s') . '] END');
        return true;
    }
}
