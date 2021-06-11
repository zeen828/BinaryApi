<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaravelAdmin03PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * 指令:: php artisan db:seed --class=LaravelAdmin03PermissionsSeeder
     * 
     * @return void
     */
    public function run()
    {
        $now_date = date('Y-m-d h:i:s');

        // 權限
        DB::table('admin_permissions')->truncate();
        DB::table('admin_permissions')->insert([
            // system
            // 可用號碼1~6(使用到:6)
            ['id' => '1', 'name' => 'All permission', 'slug' => '*', 'http_method' => '', 'http_path' => "*\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '2', 'name' => '儀表板', 'slug' => 'dashboard', 'http_method' => 'GET', 'http_path' => "/dashboard\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '3', 'name' => '登入登出', 'slug' => 'auth.login', 'http_method' => '', 'http_path' => "/auth/login\r\n/auth/logout\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '4', 'name' => '會員設定', 'slug' => 'auth.setting', 'http_method' => 'GET,PUT', 'http_path' => "/auth/setting\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '5', 'name' => 'Auth management', 'slug' => 'auth.management', 'http_method' => '', 'http_path' => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs\r\n", 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '6', 'name' => 'Admin helpers', 'slug' => 'ext.helpers', 'http_method' => '', 'http_path' => "/helpers/*", 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
    }
}
