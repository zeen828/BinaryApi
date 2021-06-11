<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// 方法一:
// Route::get('/test', 'App\Http\Controllers\Tests\IndexController@index')->name('tetst');
// 方法二:
// Route::get('/test', [\App\Http\Controllers\Tests\IndexController::class, 'index'])->name('tetst');

// API版本
Route::group([
    // 'middleware' => 'auth:api',
    'prefix' => 'v1',
    'as' => 'api.v1.',
], function ($router) {
    // 測試用
    // Route::group([
    //     'middleware' => ['jwt.verify'],
    //     'prefix' => 'debug',
    //     'as' => 'debug.',
    // ], function ($router) {
    //     // 對接會員 - 平台
    //     Route::get('platform', [\App\Http\Controllers\Api\DockingController::class, 'platform'])->name('platform');
    //     // 會員 - 資訊
    //     Route::get('info', [\App\Http\Controllers\Api\UserController::class, 'info'])->name('info');
    // });
    // 與平台對接用(或第三方)
    Route::group([
        'prefix' => 'docking',
        'as' => 'docking.',
    ], function ($router) {
        // 對接 - 生成簽章
        // Route::get('signature', [\App\Http\Controllers\Api\DockingController::class, 'signature'])->name('signature');
        // 對接 - 更新Token
        Route::put('token/refresh', [\App\Http\Controllers\Api\DockingController::class, 'tokenRefresh'])->name('token.refresh');
        // 對接會員 - 平台
        Route::get('platform', [\App\Http\Controllers\Api\DockingController::class, 'platform'])->name('platform');
        // 會員 - 登入/註冊
        Route::post('user/auth', [\App\Http\Controllers\Api\DockingController::class, 'userAuth'])->name('user.auth');
        // 點數 - 點數匯入
        Route::put('point/import', [\App\Http\Controllers\Api\DockingController::class, 'pointImport'])->name('platform.point.import');
        // 點數 - 點數匯出
        Route::put('point/export', [\App\Http\Controllers\Api\DockingController::class, 'pointExport'])->name('platform.point.export');
    });
    // 會員資料
    Route::group([
        'middleware' => ['jwt.verify'],
        'prefix' => 'user',
        'as' => 'user.',
    ], function ($router) {
        // 會員 - 資訊
        Route::get('info', [\App\Http\Controllers\Api\UserController::class, 'info'])->name('info');
        // 會員 - 紀錄 - 訂單
        Route::get('record/order', [\App\Http\Controllers\Api\UserController::class, 'recordOrder'])->name('record.order');
        // 會員 - 紀錄 - 投注
        Route::get('record/betting', [\App\Http\Controllers\Api\UserController::class, 'RecordBetting'])->name('record.betting');
    });
    // 二元期權
    Route::group([
        'middleware' => ['jwt.verify'],
        'prefix' => 'binary',
        'as' => 'binary.',
    ], function ($router) {
        // 期權 - 清單
        Route::get('list', [\App\Http\Controllers\Api\BinaryController::class, 'list'])->name('list');
    });
    // 二元期權 > 幣種
    Route::group([
        'middleware' => ['jwt.verify'],
        'prefix' => 'currency',
        'as' => 'currency.',
    ], function ($router) {
        // 期權 - 清單
        Route::get('list', [\App\Http\Controllers\Api\CurrencyController::class, 'list'])->name('list');
        // 期權 - 走勢 - 每分鐘
        Route::get('trend/minute', [\App\Http\Controllers\Api\CurrencyController::class, 'trendMinute'])->name('trend.minute');
        // 期權 - 走勢 - 開獎
        Route::get('trend/draw', [\App\Http\Controllers\Api\CurrencyController::class, 'trendDraw'])->name('trend.draw');
        // 期權 - 圖表 - 走勢
        Route::get('chart/trend', [\App\Http\Controllers\Api\CurrencyController::class, 'chartTrend'])->name('chart.trend');
        // 期權 - 圖表 - K線圖
        Route::get('chart/k', [\App\Http\Controllers\Api\CurrencyController::class, 'chartK'])->name('chart.k');
    });
    // 投注
    Route::group([
        'middleware' => ['jwt.verify'],
        'prefix' => 'betting',
        'as' => 'betting.',
    ], function ($router) {
        // 投注 - 規則 - 類型
        Route::get('rule/type', [\App\Http\Controllers\Api\BettingController::class, 'ruleType'])->name('rule');
        // 投注 - 規則 - 幣種
        Route::get('rule/currency', [\App\Http\Controllers\Api\BettingController::class, 'ruleCurrency'])->name('rule');
        // 投注 - 下注
        Route::post('bet', [\App\Http\Controllers\Api\BettingController::class, 'bet'])->name('bet');
    });
});
