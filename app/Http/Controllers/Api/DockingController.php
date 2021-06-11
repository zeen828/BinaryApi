<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 模型
use Illuminate\Support\Facades\DB;
use App\Models\Auth\User;
use App\Models\Website\Dealer;
use App\Models\Order\Order;
// 驗證
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// JWT
use Tymon\JWTAuth\Facades\JWTAuth;
// lib
use Illuminate\Support\Facades\Http;
use App\Libraries\Docking\DockingAuth;
// 輸出格式
use App\Http\Resources\Api\Jwt\TokenResource;
use App\Http\Resources\Api\Jwt\TokenCollection;
use App\Http\Resources\Api\User\UserInfoResource;
use App\Http\Resources\Api\Point\OrderResource;
// 例外處理
use App\Exceptions\Auth\DockingException;
use App\Exceptions\Binary\UserException;
// Redis
use Illuminate\Support\Facades\Redis;

class DockingController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:jwt', ['except' => ['signature', 'platform']]);
        $this->middleware('jwt.verify', ['except' => ['signature', 'platform', 'userAuth']]);
    }

    // protected function guard()
    // {
    //     return Auth::guard('jwt');
    // }

    public function index(Request $request)
    {
        $data = array('Controller' => 'DockingController', 'function' => 'index', 'request' => $request);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    // 對接 - 生成簽章
    // http://127.0.0.1:8000/api/v1/docking/signature
    public function signature(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'iv' => 'required|string',
        //     'key' => 'required|string',
        // ]);

        // if($validator->fails())
        // {
        //     return response()->json($validator->errors(), 422);
        // }

        // 經銷商檢查(iv明碼key加密做檢驗)
        $dealer = Dealer::where('iv', 'platform')->first();
        if(empty($dealer)){
            echo '錯誤!!';
        }

        // 加密Key
        $strEncode = Hash::make($dealer->key);

        return response()->json([
            'dealer_key' => $strEncode,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);

        // $signature = encrypt($iv . $key);

        // $uid = '18975649851';
        // $user = User::create([
        //     'name' => $uid,
        //     'email' => sprintf('', $uid),
        //     'password' => sprintf('', $uid),
        // ]);

        // // $token = JWTAuth::encode($tmp)->get();
        // // eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYxNzE2MjA5MiwiZXhwIjoxNjE3MTY1NjkyLCJuYmYiOjE2MTcxNjIwOTIsImp0aSI6IjEySUwxRHJtQk9uRFVkRGgiLCJzdWIiOjIsInBydiI6ImI5MTI3OTk3OGYxMWFhN2JjNTY3MDQ4N2ZmZjAxZTIyODI1M2ZlNDgifQ.y4RGQeJ76LD-aEdbt9aWwPHo7HhOevsOfU5M2YTV9U0

        // $data = array('Controller' => 'DockingController', 'function' => 'signature', 'request' => $request, 'signature' => $signature, 'token' => $token, );
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    // 對接 - 更新Token
    public function tokenRefresh(Request $request)
    {
        // 刷新token
        $token = JWTAuth::parseToken()->refresh();
        return new TokenResource([
            // 'user' => new InfoOne($user),
            'access_token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            // 'request' => $request->all(),
        ]);
    }

    // 對接會員 - 平台
    // http://127.0.0.1:8000/api/v1/docking/platform?iv=platform&key=$2y$10$H70vhHfsMe/QOlhxdlf2Ze2M1jAsdT34VUfY2lmTAnS.lvOQLlNYu&signature=123signature
    public function platform(Request $request)
    {
        // Validator驗證
    	$validator = Validator::make($request->all(), [
            'iv' => 'required|string',
            'key' => 'required|string',
            'signature' => 'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        // 經銷商檢查(iv明碼key加密做檢驗)
        $input = $validator->validated();
        $dealer = Dealer::where('iv', $input['iv'])->first();
        if (empty($dealer))
        {
            throw new DockingException('DOCKING_DEALER_IV');
            // abort(403, '查無經銷商');
        }
        // if (!Hash::check($dealer->key, $input['key']))
        // {
        //     throw new DockingException('DOCKING_DEALER_KEY');
        //     // abort(403, '經銷商驗證Key錯誤');
        // }

        // 模擬對接資料
        if (true)
        {
            $resHeaderData = [
                'content-type' => 'application/json',
            ];
            $resData = [
                'success' => true,
                'data' => [
                    'uid' => '20210406',
                    'inviter_uid' => '',
                    'expires_in' => '86400',
                ]
            ];
            Http::fake([
                '*' => Http::response($resData, 200, $resHeaderData),
            ]);
        }

        // 對接平台資料
        $docking = new DockingAuth();
        $docking->thirdAuth($input['signature']);
        $uid = $docking->getUserUid();
        if (empty($uid))
        {
            throw new DockingException('DOCKING_USER_UID');
            // abort(403, '對接會員取得出錯');
        }

        // 用戶查詢
        $account = array(
            'name' => $uid,
            'email' => sprintf('%s@%s.docking.tw', $uid, $input['iv']),
            'password' => sprintf('%s-%s', $uid, $input['iv']),
        );
        $user = User::where('email', $account['email'])->where('status', '1')->first();
        if(empty($user))
        {
            // 註冊新用戶
            $saveData = array_merge(
                $account,
                array(
                    'password' => bcrypt($account['password']),
                    'status' => '1',
                )
            );
            $user = User::create($saveData);
        }

        // Token處理輸出
        $token = JWTAuth::fromUser($user);
        return new TokenResource([
            // 'user' => new InfoOne($user),
            'access_token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            // 'request' => $request->all(),
        ]);
    }

    // 對接會員 - 平台 - 登入/註冊
    public function userAuth(Request $request)
    {
        // Validator驗證
        $validator = Validator::make($request->all(), [
            'iv' => 'required|string',
            'key' => 'required|string',
            'account' => 'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        $input = $validator->validated();

        // 經銷商檢查(iv明碼key加密做檢驗)
        $dealer = Dealer::where('iv', $input['iv'])->first();
        if (empty($dealer))
        {
            throw new DockingException('DOCKING_DEALER_IV');
            // abort(403, '查無經銷商');
        }
        if ($dealer->key != $input['key'])
        {
            throw new DockingException('DOCKING_DEALER_KEY');
            // abort(403, '經銷商驗證Key錯誤');
        }

        // 用戶查詢
        $account = array(
            'name' => $input['account'],
            'email' => sprintf('%s@%s.user.auth', $input['account'], $input['iv']),
            'password' => sprintf('%s-%s', $input['account'], $input['iv']),
        );
        $user = User::where('email', $account['email'])->where('status', '1')->first();
        if(empty($user))
        {
            // 註冊新用戶
            $saveData = array_merge(
                $account,
                array(
                    'password' => bcrypt($account['password']),
                    'status' => '1',
                )
            );
            $user = User::create($saveData);
        }

        // Token處理輸出
        $token = JWTAuth::fromUser($user);
        return new TokenResource([
            // 'user' => new InfoOne($user),
            'access_token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            // 'request' => $request->all(),
        ]);
    }

    // 對接會員 - 平台 - 點數匯入
    public function pointImport(Request $request)
    {
        // Validator驗證
        $validator = Validator::make($request->all(), [
            'order_sn' => 'required|string',
            'point' => 'required|integer',
            'verify' => 'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        $input = $validator->validated();
        // 會員資料
        $user = Auth::user();

        // echo md5(sprintf('jasn_%s_jasn', $input['point']));exit();
        // 200 => 215b43b2cc5981c48ce499d5a0b2eb0a
        // 驗證點數
        if($input['verify'] != md5(sprintf('jasn_%s_jasn', $input['point']))){
            throw new DockingException('DOCKING_POINT_VERIFY');
        }

        // 訂單流水號
        $key = sprintf('Dockint:Order');
        $order = Redis::get($key);
        if(empty($order))
        {
            $order = 0;
        }
        $order++;

        // 交易模式
        DB::beginTransaction();
        // User
        // $user = Auth::user();
        $user->point += $input['point'];
        $user->save();

        // 建立訂單
        $pointOrder = Order::create([
            'sn' => sprintf('B-%s-%06d-%s', date('YmdHis'), $order, time()),
            'user_id' => $user->id,
            'order_sn' => $input['order_sn'],
            'event' => 'deposit',
            'point' => $input['point'],
            'remarks' => '轉入',
            'status' => 1,
        ]);
        //
        Redis::set($key, $order);
        DB::commit();

        // 另外帶進去紀錄，前端更新用
        $user->order_id = $pointOrder->id;
        $user->order_sn = $pointOrder->sn;
        $user->order_order_sn = $pointOrder->order_sn;
        $user->order_event = $pointOrder->event;
        $user->order_point = $pointOrder->point;
        $user->order_remarks = $pointOrder->remarks;

        return new OrderResource($user);
    }

    // 對接會員 - 平台 - 點數匯出
    public function pointExport(Request $request)
    {
        // Validator驗證
        $validator = Validator::make($request->all(), [
            'order_sn' => 'required|string',
            'point' => 'required|integer',
            'verify' => 'required|string',
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        $input = $validator->validated();
        // 會員資料
        $user = Auth::user();

        // echo md5(sprintf('jasn_%s_jasn', $input['point']));exit();
        // 200 => 215b43b2cc5981c48ce499d5a0b2eb0a
        // 驗證點數
        if($input['verify'] != md5(sprintf('jasn_%s_jasn', $input['point']))){
            throw new DockingException('DOCKING_POINT_VERIFY');
        }
        // 檢查會員點數是否足夠扣除
        if($user->point < $input['point']){
            throw new UserException('USER_POINT_NOT_ENOUGH');
        }

        // 訂單流水號
        $key = sprintf('Dockint:Order');
        $order = Redis::get($key);
        if(empty($order))
        {
            $order = 0;
        }
        $order++;

        // 交易模式
        DB::beginTransaction();
        // User
        // $user = Auth::user();
        $user->point -= $input['point'];
        $user->save();

        // 建立訂單
        $pointOrder = Order::create([
            'sn' => sprintf('B-%s-%06d-%s', date('YmdHis'), $order, time()),
            'user_id' => $user->id,
            'order_sn' => $input['order_sn'],
            'event' => 'payment',
            'point' => $input['point'],
            'remarks' => '轉出',
            'status' => 1,
        ]);
        //
        Redis::set($key, $order);
        DB::commit();

        // 另外帶進去紀錄，前端更新用
        $user->order_id = $pointOrder->id;
        $user->order_sn = $pointOrder->sn;
        $user->order_order_sn = $pointOrder->order_sn;
        $user->order_event = $pointOrder->event;
        $user->order_point = $pointOrder->point;
        $user->order_remarks = $pointOrder->remarks;

        return new OrderResource($user);
    }
}
