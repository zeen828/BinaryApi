<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group([
    // 'middleware' => 'auth:web',
], function ($router) {
    Route::get('/', [\App\Http\Controllers\VueJS\IndexController::class, 'index'])->name('home.index');// Vue假畫面
    Route::get('/docking', [\App\Http\Controllers\VueJS\IndexController::class, 'docking'])->name('docking');// 對接
    Route::get('/debug/login', [\App\Http\Controllers\VueJS\IndexController::class, 'debugLogin'])->name('debug.login');// 除錯-登入
    Route::get('/debug/logout', [\App\Http\Controllers\VueJS\IndexController::class, 'debugLogout'])->name('debug.logout');// 除錯-登出
});

Route::get('login', function () {
//     abort(403, '未登入');
    return response()->json(['message' => '這是登入錯誤會跑來的頁面!'], 403);
})->name('login');

Route::get('test/event/{id}', [\App\Http\Controllers\VueJS\IndexController::class, 'event']);
Route::get('test/lib', [\App\Http\Controllers\VueJS\IndexController::class, 'lib']);
