<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 模型
use Illuminate\Support\Facades\DB;
use App\Models\User\User;
use App\Models\User\UserBetting;
use App\Models\Order\Order;
// 驗證
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// 輸出格式
use App\Http\Resources\Api\User\UserInfoResource;
use App\Http\Resources\Api\User\UserInfoCollection;
use App\Http\Resources\Api\User\RecordOrderResource;
use App\Http\Resources\Api\User\RecordOrderCollection;
use App\Http\Resources\Api\User\RecordBettingResource;
use App\Http\Resources\Api\User\RecordBettingCollection;
// 輔助工具
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:jwt', ['except' => ['index',]]);
        $this->middleware('jwt.verify', ['except' => ['index',]]);
    }

    // protected function guard()
    // {
    //     return Auth::guard('jwt');
    // }

    public function index(Request $request)
    {
        $data = array('Controller' => 'UserController', 'function' => 'index', 'request' => $request);

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * 2021/05/05 完成
     * 會員 - 資訊
     * 
     * @回傳：
     * 用戶資訊(即時無緩存)
     */
    public function info(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            return new UserInfoResource($user);
        }
    }

    /**
     * 2021/05/05 完成
     * 會員 - 紀錄 - 訂單
     * 
     * @輸入：
     * page：頁數
     * 
     * @回傳：
     * 用戶紀錄訂單資料(有分頁)
     */
    public function recordOrder(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // print_r($user);exit();
            $page = $request->get('page', 1);
            // [緩存]用戶-紀錄-訂單
            $key = sprintf('User:Record:Order:uid_%d:page_%s:%s', $user->id, $page, date('YmdHi'));
            $data = Cache::remember($key, env('CACHE_TIME', 12000), function () use ($user) {
                $data = Order::select('*')
                    ->where('user_id', $user->id)->where('status', '1')
                    ->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')
                    // ->get();
                    ->paginate(10);
                    // ->find(1);

                return $data;
            });

            return new RecordOrderCollection($data);
        }
    }

    /**
     * 2021/05/05 完成
     * 會員 - 紀錄 - 投注
     * 
     * @輸入：
     * page：頁數
     * 
     * @回傳：
     * 用戶紀錄投注資料(有分頁)
     */
    public function RecordBetting(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // print_r($user);exit();
            $page = $request->get('page', 1);
            // [緩存]用戶-紀錄-投注
            $key = sprintf('User:Record:Betting:uid_%d:page_%s:%s', $user->id, $page, date('YmdHi'));
            // $data = Cache::remember($key, env('CACHE_TIME', 12000), function () use ($user) {
                $data = UserBetting::with('rCurrency')->select('*')
                    ->where('user_id', $user->id)->where('status', '1')
                    ->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')
                    // ->get();
                    ->paginate(10);
                    // ->find(1);

                return $data;
            // });

            return new RecordBettingCollection($data);
        }
    }
}
