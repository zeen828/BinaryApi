<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 模型
use Illuminate\Support\Facades\DB;
use App\Models\User\UserBetting;
use App\Models\Binary\BinaryCurrency;
use App\Models\Binary\BinaryCurrencyTrend;
use App\Models\Binary\BinaryRuleType;
use App\Models\Binary\BinaryRuleCurrency;
// 驗證
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// 輸出格式
use App\Http\Resources\Api\Betting\BettingRuleTypeResource;
use App\Http\Resources\Api\Betting\BettingRuleTypeCollection;
use App\Http\Resources\Api\Betting\BettingRuleCurrencyResource;
use App\Http\Resources\Api\Betting\BettingRuleCurrencyCollection;
use App\Http\Resources\Api\Betting\BettingInfoResource;
use App\Http\Resources\Api\Betting\BettingInfoCollection;
// 例外處理
use App\Exceptions\Binary\BinaryException;
use App\Exceptions\Binary\BettingException;
// 輔助工具
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BettingController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:jwt', ['except' => ['index', 'rule',]]);
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

    // 投注 - 規則 - 類型
    public function ruleType(Request $request)
    {
        // 規則 - 分類 - 緩存
        $key = sprintf('Rule:Type:All:%s', date('YmdH'));
        $data = Cache::remember($key, env('CACHE_TIME', 12000), function () {
            $data = BinaryRuleType::where('status', '1')
                ->orderBy('sort', 'ASC')
                ->get();

            return $data;
        });

        return new BettingRuleTypeCollection($data);
    }

    // 投注 - 規則 - 幣種
    public function ruleCurrency(Request $request)
    {
        // 輸入檢查 - Validator驗證
    	$validator = Validator::make($request->all(), [
            'currency_id' => 'required|integer',
        ]);
        // 輸入錯誤處理
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        // 輸入轉變數
        $input = $validator->validated();

        // 規則 - 幣種 - 緩存
        $key = sprintf('Rule:Currency:cid_%d:%s', $input['currency_id'], date('YmdH'));
        $data = Cache::remember($key, env('CACHE_TIME', 12000), function () use ($input) {
            $data = BinaryRuleCurrency::where('binary_currency_id', $input['currency_id'])
                ->where('status', '1')
                ->orderBy('sort', 'ASC')
                ->get();

            return $data;
        });

        return new BettingRuleCurrencyCollection($data);
    }

    // 投注 - 下注
    public function bet(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'currency_id' => 'required|integer',    // 幣種
            'rule_id' => 'required|integer',        // 規則
            'rule_value' => 'required|string',      // 規則值
            'quantity' => 'required|integer',       // 數量
            'amount' => 'required|integer',         // 金額
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        // 輸入轉變數
        $input = $validator->validated();
        // print_r($input);

        // User
        $user = Auth::user();
        // print_r($user);
        // print_r($input['quantity'] * $input['amount']);
        // 檢查可扣點數
        if($user->point < ($input['quantity'] * $input['amount'])){
            throw new BettingException('USER_POINT_NOT_ENOUGH');
        }

        // 幣種 - 緩存
        $key = sprintf('BinaryCurrency:cid_%d:%s', $input['currency_id'], date('YmdH'));
        $currency = Cache::remember($key, env('CACHE_TIME', 12000), function () use ($input) {
            $data = BinaryCurrency::where('status', '1')
                // ->orderBy('sort', 'ASC')
                ->find($input['currency_id']);

            return $data;
        });
        if (empty($currency))
        {
            throw new BinaryException('BINARY_CURRENCY_NOT_FOUND');
        }

        // 規則 - 緩存
        $key = sprintf('Rule:Currency:rid_%d:%s', $input['rule_id'], date('YmdH'));
        $rule = Cache::remember($key, env('CACHE_TIME', 12000), function () use ($input) {
            return BinaryRuleCurrency::where('binary_currency_id', $input['currency_id'])
                ->where('status', '1')
                ->find($input['rule_id']);
        });
        if (empty($rule))
        {
            throw new BinaryException('BINARY_RULE_CURRENCY_NOT_FOUND');
        }
        // 檢查投注是否事下注值的規則
        $betVal = json_decode($rule->bet_json, true);
        if (!array_key_exists($input['rule_value'], $betVal))
        {
            throw new BinaryException('BINARY_RULE_CURRENCY_NOT_FOUND');
        }

        // (即時)幣種走勢期數
        $now = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
        // var_dump($now);//2021-06-10 10:54:59
        // exit();
        $trend = BinaryCurrencyTrend::where('binary_currency_id', $input['currency_id'])
            ->where('bet_at', '<=', $now)//2021-06-09 18:54:00
            ->where('stop_at', '>=', $now)//2021-06-09 18:54:50
            ->where('redeem', '0')->where('status', '1')
            ->orderBy('draw_at', 'desc')
            ->first();
        // print_r($trend);exit();
        if (empty($trend))
        {
            throw new BinaryException('BINARY_CURRENCY_TREND_NOT_FOUND');
        }

        DB::beginTransaction();
        // 扣點數
        $delPoint = $input['quantity'] * $input['amount'];
        $user->decrement('point', $delPoint);
        // $user->save();

        // 統計-欄位遞增
        $trend->increment($input['rule_value']);
        // $trend->save();

        // 建立投注資料
        $data = UserBetting::create([
            'user_id' => $user->id,
            'binary_currency_id' => $input['currency_id'],
            'binary_currency_trend_id' => $trend->id,
            'binary_rule_currency_id' => $input['rule_id'],
            'binary_rule_currency_value' => $input['rule_value'],
            'quantity' => $input['quantity'],
            'amount' => $input['amount'],
            'profit' => $input['amount'] * $rule->odds,
            'status' => '1',
        ]);
        DB::commit();

        return new BettingInfoResource($data);
    }
}
