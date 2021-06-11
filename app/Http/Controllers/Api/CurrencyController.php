<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 模型
use Illuminate\Support\Facades\DB;
use App\Models\Binary\Binary;
use App\Models\Binary\BinaryCurrency;
use App\Models\Binary\BinaryCurrencyChart;
use App\Models\Binary\BinaryCurrencyTrend;
use App\Models\Binary\BinaryRuleType;
use App\Models\Binary\BinaryRuleCurrency;
// 驗證
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// 輸出格式
use App\Http\Resources\Api\Currency\CurrencyResource;
use App\Http\Resources\Api\Currency\CurrencyCollection;
use App\Http\Resources\Api\Currency\TrendMinuteResource;
use App\Http\Resources\Api\Currency\TrendMinuteCollection;
use App\Http\Resources\Api\Currency\TrendDrawResource;
use App\Http\Resources\Api\Currency\TrendDrawCollection;
use App\Http\Resources\Api\Currency\ChartTrendResource;
use App\Http\Resources\Api\Currency\ChartTrendCollection;
use App\Http\Resources\Api\Currency\ChartKResource;
use App\Http\Resources\Api\Currency\ChartKCollection;
// 例外處理
use App\Exceptions\Auth\JwtException;
// 輔助工具
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CurrencyController extends Controller
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
        $data = array('Controller' => 'BinaryController', 'function' => 'index', 'request' => $request);

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    // 期權 - 清單
    // http://127.0.0.1:8000/api/v1/binary/list
    public function list(Request $request)
    {
        // // 輸入檢驗
    	// $validator = Validator::make($request->all(), [
        //     'start' => 'required|date',
        //     'end' => 'required|date',
        // ]);
        // if($validator->fails())
        // {
        //     return response()->json($validator->errors(), 422);
        // }
        // $input = $validator->validated();
        // $input['start'] = Carbon::today();
        // $input['end'] = Carbon::tomorrow();
        // $input['start'] = '2021-03-30 15:00:00';
        // $input['end'] = '2021-03-30 16:00:00';
        $input['start'] = Carbon::yesterday();// 昨天
        $input['end'] = Carbon::tomorrow();// 明天

        // [緩存]期權 - 幣種
        $key = sprintf('BinaryCurrency:List:%s', date('YmdHi'));
        $data = Cache::remember($key, env('CACHE_TIME', 12000), function () use ($input) {
            // 處理資料
            $tmpTrend = [];
            $data = [];

            // 走勢最新的一筆
            $subQuery = DB::table(
                BinaryCurrencyTrend::whereBetween('draw_at', array($input['start'], $input['end']))
                    ->where('status', '1')
                    ->orderBy('binary_currency_id', 'ASC')
                    ->orderBy('draw_at', 'ASC')
                    // ->toSql();
                , 'orderTable')
                ->selectRaw('binary_currency_id, MIN(draw_at)')
                ->groupBy('binary_currency_id');
                // ->toSql();
            // 子查詢
            $trends = BinaryCurrencyTrend::select('id', 'binary_currency_id', 'period', 'draw_at', 'trend')
                ->whereIn(DB::raw('(binary_currency_id, draw_at)'), $subQuery)
                ->get();
            if(!$trends->isEmpty()){
                foreach ($trends as $trend) {
                    $tmpTrend[$trend->binary_currency_id] = $trend;
                }
            }

            // 幣種 - 緩存
            $key = sprintf('BinaryCurrency:All:%s', date('YmdH'));
            $currencys = Cache::remember($key, env('CACHE_TIME', 12000), function () {
                return BinaryCurrency::with('rBinary')
                    ->select('*')
                    ->where('status', '1')
                    ->orderBy('sort', 'ASC')
                    ->get();
            });
            if($currencys->isEmpty()){
                abort(403, '缺幣種');
            }
            foreach ($currencys as $currency) {
                $currency->period = isset($tmpTrend[$currency->id])? $tmpTrend[$currency->id]->period : 'DEMO-PERIOD';
                $currency->draw_at = isset($tmpTrend[$currency->id])? $tmpTrend[$currency->id]->draw_at : 'DEMO-DRAW-AT';
                $currency->trend = isset($tmpTrend[$currency->id])? $tmpTrend[$currency->id]->trend : '0000.00';
                $data[] = $currency;
            }

            return $data;
        });

        return new CurrencyCollection($data);
    }

    // 期權 - 走勢 - 每分鐘
    public function trendMinute(Request $request)
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

        $query_start_at = '2021-03-30 15:00:00';
        $query_end_at = '2021-03-30 16:00:00';

        // 嚴格模式下需要這樣處理group by，group by與order by不可同時使用，
        // 1. 依照 `draw_at` 跟 `status` 篩選目標資料，在用 `binary_currency_id` 和 `draw_at` 做排序
        // 2. 分組 `binary_currency_id` 來獲取資料，上一步已經排序過所以這樣可以取得不重複 `binary_currency_id` 且時間最接近 MIN(`draw_at`) 的資料
        // 3. 再拿獲取後的資料重新取出要的資料
        //
        // SELECT * 
        // FROM `binary_currency_trend`
        // WHERE (`binary_currency_id`, `draw_at`) IN (
        //     SELECT `binary_currency_id`, MIN(`draw_at`)
        //     FROM (
        //         SELECT *
        //         FROM `binary_currency_trend`
        //         WHERE `draw_at` BETWEEN '2021-03-30 15:00:00' AND '2021-03-30 17:00:00'
        //         AND `status` = '1'
        //         ORDER BY `binary_currency_id`, `draw_at`
        //     ) AS `tmpe1`
        //     GROUP BY `binary_currency_id`
        // )
        
        // 查詢資料範圍(準備子查詢用)
        $subQuery = DB::table(
            BinaryCurrencyTrend::whereBetween('draw_at', array($query_start_at, $query_end_at))
                ->where('status', '1')
                ->orderBy('binary_currency_id', 'ASC')
                ->orderBy('draw_at', 'ASC')
                // ->toSql();
            , 'orderTable')
            ->selectRaw('binary_currency_id, MIN(draw_at)')
            ->groupBy('binary_currency_id');
            // ->toSql();

        // 子查詢
        $data = BinaryCurrencyTrend::select('id', 'binary_currency_id', 'period', 'draw_at', 'trend')
            ->whereIn(DB::raw('(binary_currency_id, draw_at)'), $subQuery)
            ->get();
            // ->toSql();

        return new TrendMinuteCollection($data);
    }

    // 期權 - 走勢 - 開獎
    public function trendDraw(Request $request)
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

        $query_currency_id = '1';
        $query_start_at = '2021-03-30 15:30:00';
        $query_end_at = '2021-03-30 16:00:00';

        $data = BinaryCurrencyTrend::select('id', 'binary_currency_id', 'period', 'bet_at', 'stop_at', 'draw_at', 'trend', 'forecast')
            ->where('binary_currency_id', $query_currency_id)
            ->whereBetween('draw_at', array($query_start_at, $query_end_at))
            ->where('status', '1')
            ->orderBy('draw_at', 'asc')
            ->get();

        return new TrendDrawCollection($data);
    }

    public function chartTrend(Request $request)
    {
        // 輸入檢查 - Validator驗證
    	$validator = Validator::make($request->all(), [
            'currency_id' => 'required|integer',
            'draw_at' => 'required|string',
        ]);
        // 輸入錯誤處理
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        // 輸入轉變數
        $input = $validator->validated();

        // throw new JwtException('TEST_01');
        // try {
        //     $res = 1/0;
        // } catch (JwtException $e) {
        //     throw new JwtException(500, 'SMS_SEND_FAILED');
        // }
        // $query_currency_id = '1';
        // $query_start_at = '2021-04-01 00:00:00';
        // $query_end_at = '2021-04-30 00:00:00';

        // 該幣種最新一筆資料
        $data = BinaryCurrencyTrend::select('*')
            ->where('binary_currency_id', $input['currency_id'])
            // ->where('draw_at', '>', $input['draw_at'])
            ->where('status', '1')
            ->orderBy('draw_at', 'desc')
            ->limit(1)
            ->get();
            // ->first();
            // ->toSql();
        // print_r($data);exit();

        return new ChartTrendCollection($data);
    }

    /**
     * 2021/05/03 完成
     * 圖表-K線圖
     * 
     * @輸入：
     * currency_id：期權幣種ID
     * 
     * @回傳：
     * 期權幣種最新的30筆K線走勢
     */
    public function chartK(Request $request)
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

        // [緩存]期權 - 圖表 - K線圖
        $key = sprintf('Chart:KLine:cid_%d:%s', $input['currency_id'], date('Ymd'));
        $data = Cache::remember($key, env('CACHE_TIME', 12000), function () use ($input) {
            // 先取得目標ids，
            $ids = BinaryCurrencyChart::where('binary_currency_id', $input['currency_id'])
                ->where('status', '1')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->pluck('id');
                // 做排序
                return BinaryCurrencyChart:: WhereIN('id', $ids)
                    ->orderBy('date', 'asc')
                    ->get();
        });

        return new ChartKCollection($data);
    }
}
