<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    // 網站
    $router->resource('website/website', Website\WebsiteController::class);
    $router->resource('website/dealer', Website\DealerController::class);
    $router->resource('website/bulletins', Website\BulletinController::class);
    // 用戶
    $router->resource('user/users', User\UserController::class);
    $router->resource('user/bettings', User\BettingController::class);
    // 訂單
    $router->resource('order/enter', Order\EnterController::class);
    $router->resource('order/out', Order\OutController::class);
    $router->resource('order/betting', Order\BetController::class);
    // 期權
    $router->resource('binary/binaries', Binary\BinaryController::class);
    $router->resource('binary/currencies', Binary\CurrencyController::class);
    $router->resource('binary/rules', Binary\RuleController::class);
    $router->resource('binary/trends', Binary\TrendController::class);
    // 數據
});
