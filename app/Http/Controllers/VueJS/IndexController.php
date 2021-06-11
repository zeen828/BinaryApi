<?php

namespace App\Http\Controllers\VueJS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User\User;
use App\Models\Binary\Binary;
// Redis
use Redis as NewRedis;
use Illuminate\Support\Facades\Redis;
use App\Models\Binary\BinaryCurrencyTrend;
// use App\Events\Binary\EventDraw;
// use App\Jobs\SendReminderEmail;
// use database\factories\Binary\BinaryCurrencyTrendFactory;
// LIB測試
use App\Models\Binary\BinaryCurrency;
// use App\Models\Binary\BinaryCurrencyTrend;
use App\Libraries\Binary\BinaryDraw;
use App\Libraries\Binary\Third\NomicsApi;

class IndexController extends Controller
{
    // 除錯-模擬web登入
    public function debugLogin(Request $request)
    {
        // 取得變數
        $signature = $request->input('signature');
        var_dump($signature);

        // 取得用戶資訊，登入
        $user = User::find(1);
        var_dump($user);
        Auth::login($user, true);

        // 檢查是否登入
        if (Auth::check()) {
            echo '已登入!';
        }
    }

    // 除錯-模擬web登出
    public function debugLogout()
    {
        Auth::logout();
    }

    // 二元畫面
    public function index(Request $request)
    {
        // 檢查
        if (Auth::check()) {
            // 取得用戶資訊
            $user = Auth::user();
            $user->point = 100;

            $binary = Binary::where('status', '1')->get();
            // 登入，單頁Vue.js
            return view('home.index', [
                'user' => $user,
                'binary' => $binary,
            ]);
        } else {
            // 未登入，權限不足
            // abort(403);
            echo '未登入，權限不足';
        }
    }

    // 對接登入
    public function docking(Request $request)
    {
        // 簽章
        $signature = $request->input('signature');
        // var_dump($signature);

        // 註冊&取得會員資料
        $user = User::find(1);
        var_dump($user);

        // 會員登入
        Auth::login($user, true);

        // 跳轉首頁
        if (Auth::check()) {
            return redirect('/');
        }
    }

    public function event($id, Request $request)
    {
        $domain = '127.0.0.1';
        $channelName = "private-channel-name";

        // $trend = BinaryCurrencyTrend::find(2);
        switch($id)
        {
            case 1:
                echo 'Setup 1：Laravel 事件驅動監聽(好難失敗了)', "<br/>\n";
                // $user = User::find(1);
                // $uid = $user->id;
                // event(new EventDraw($uid));
                break;
            case 2:
                echo 'Setup 2：第一種PHP連Redis', "<br/>\n";
                // https://www.mdeditor.tw/pl/2Kbf/zh-tw
                $redis = new NewRedis();
                $redis->pconnect($domain, 6379);
                var_dump($redis);
                $data = array(
                    'key' => 'key',
                    'data' => 'testdata',
                );
                $param = array(
                    'publish',
                    $channelName,
                    json_encode($data),
                );
                $ret = call_user_func_array(array($redis, 'rawCommand'), $param);
                var_dump($ret);
                break;
            case 3:
                echo 'Setup 3：第二種PHP連Redis', "<br/>\n";
                // https://www.jianshu.com/p/cec941a228a6
                $redis = new NewRedis();
                $redis->connect($domain, 6379);
                $order = [
                    'endAt' => '2021-05-19 16:27:30',
                    'currency' => 1,
                    'forecast' => [
                        [
                            'name' => '16:27:11',
                            'value' => [
                                '2021-05-19 16:27:11',
                                '47917.82',
                            ],
                        ],
                        [
                            'name' => '16:27:12',
                            'value' => [
                                '2021-05-19 16:27:12',
                                '47517.82',
                            ],
                        ],
                    ],
                ];
                $redis->publish($channelName, json_encode($order));
                break;
            case 4:
                echo 'Setup 4：Laravel連Redis', "<br/>\n";
                Redis::publish($channelName, json_encode(['foo' => 'bar']));
                break;
            case 5:
                echo 'Setup 5：DB監聽', "<br/>\n";
                DB::listen();
                break;
            case 6:
                echo 'Setup 6：任務監聽', "<br/>\n";
                // $this->dispatch(new SendReminderEmail());
                // $job = (new SendReminderEmail())->delay(60 * 50);
                // $this->dispatch($job);
                break;
            case 7:
                echo 'Setup 7：生成假資料', "<br/>\n";
                $currencys = factory(BinaryCurrencyTrend::class)->times(10)->make();
                BinaryCurrencyTrend::insert($currencys->toArray());
                BinaryCurrencyTrend::factory()->count(500)->create();
                break;
            case 8:
                echo 'Setup 8：清除Redis走勢給WS用資料', "<br/>\n";
                $keyTrend = sprintf('Trend:Channel:%s', 1);
                Redis::del($keyTrend);

                $keyTrend = sprintf('Trend:Channel:%s', 2);
                Redis::del($keyTrend);

                $keyTrend = sprintf('Trend:Channel:%s', 3);
                Redis::del($keyTrend);

                $keyTrend = sprintf('Trend:Channel:%s', 4);
                Redis::del($keyTrend);

                $keyTrend = sprintf('Trend:Channel:%s', 5);
                Redis::del($keyTrend);
                break;
            default:
                echo 'Setup default：', "<br/>\n";
                break;
        }
    }

    public function lib(Request $request)
    {
        echo 'Setup 1：取得處理資料', "<br/>\n";
        // $currency = BinaryCurrency::find(1);
        // print_r($currency);

        $trend = BinaryCurrencyTrend::with('rCurrency')->find(1);
        // print_r($trend);

        $currency = [];
        $api = new NomicsApi();
        $apiData = $api->getTicker('', '');
        $apiData = json_decode($apiData, true);
        // print_r($apiData);
        foreach ($apiData as $key=>$val)
        {
            // print_r($val);
            $currency[$val['currency']] = $val['price'];
        }
        unset($val, $apiData);
        // print_r($currency);

        echo 'Setup 2：啟動開獎函式庫', "<br/>\n";
        $testId = 2;
        switch($testId)
        {
            case 1:
                echo '直接帶入Model', "<br/>\n";
                 // 直接帶入Model
                $libDraw = new BinaryDraw($trend);
                break;
            case 2:
                echo '讀取Model', "<br/>\n";
                // 讀取Model
                $libDraw = new BinaryDraw();
                // 1.讀取Model資料
                // $libDraw->loadModel($trend->id);
                $libDraw->loadModel(2);
                $libDraw->setTrend(0);
                break;
            default:
                echo '手動輸入', "<br/>\n";
                // 手動輸入
                $libDraw = new BinaryDraw();
                // 1.設定基本變數
                $libDraw->setBaseDara($trend->rCurrency->trend_data_json);
                // 2.設定目前投注人數(單投注數，雙投注數，大投注數，小投注數，自然開獎門檻人數)
                $libDraw->setBetingLog((int) $trend->max, (int) $trend->min, (int) $trend->odd, (int) $trend->even);
                // 3.設定走勢
                // $libDraw->setTrend($trend->trend);
                // $libDraw->setTrend(3141.59);
                break;
        }

        // 4.輸入API取得走勢
        $libDraw->setTrendApi($currency[$trend->rCurrency->binary_code]);
        // 5.運作
        $libDraw->run();
        // 6.設定走勢用資料
        $libDraw->setTrendCycle($trend->bet_at, $trend->draw_at, 1, 0.06);
        // 7.走勢
        $libDraw->forecast();
        // #.取開獎號碼
        $draw = $libDraw->get();
        echo '取開獎號碼：', $draw, "<br/>\n";
        // #.取走勢
        $trend = $libDraw->getTrend();
        echo '取走勢：', $trend, "<br/>\n";
        // #.取走勢資料
        $forecast = $libDraw->getForecast();
        echo '取走勢資料：', $forecast, "<br/>\n";
        // print_r($libDraw);

        // 測試資料塞到Redis頻道
        $domain = '127.0.0.1';
        $channelName = "private-channel-name";
        $redis = new Redis();
        $redis->connect($domain, 6379);
        // $redis->publish($channelName, json_encode($data));
        $redis->publish($channelName, $forecast);
    }
}
