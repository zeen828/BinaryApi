<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaravelAdmin04MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * 指令:: php artisan db:seed --class=LaravelAdmin04MenuSeeder
     * 
     * @return void
     */
    public function run()
    {
        $now_date = date('Y-m-d h:i:s');

        // 菜單
        DB::table('admin_menu')->truncate();
        DB::table('admin_menu')->insert([
            // 首頁
            ['id' => '1', 'parent_id' => '0', 'order' => '1', 'title' => 'Index', 'icon' => 'fa-dashboard', 'uri' => '/', 'created_at' => $now_date, 'updated_at' => $now_date],
            // System
            ['id' => '2', 'parent_id' => '0', 'order' => '800', 'title' => '系統管理', 'icon' => 'fa-users', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '3', 'parent_id' => '2', 'order' => '801', 'title' => '用戶管理', 'icon' => 'fa-users', 'uri' => 'auth/users', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '4', 'parent_id' => '2', 'order' => '802', 'title' => '角色管理', 'icon' => 'fa-user', 'uri' => 'auth/roles', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '5', 'parent_id' => '2', 'order' => '803', 'title' => '權限管理', 'icon' => 'fa-ban', 'uri' => 'auth/permissions', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '6', 'parent_id' => '2', 'order' => '804', 'title' => '選單管理', 'icon' => 'fa-bars', 'uri' => 'auth/menu', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '7', 'parent_id' => '2', 'order' => '805', 'title' => 'Operation log', 'icon' => 'fa-history', 'uri' => 'auth/logs', 'created_at' => $now_date, 'updated_at' => $now_date],
            // Helpers
            ['id' => '8', 'parent_id' => '0', 'order' => '850', 'title' => 'Helpers', 'icon' => 'fa-gears', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '9', 'parent_id' => '8', 'order' => '851', 'title' => 'Scaffold', 'icon' => 'fa-keyboard-o', 'uri' => 'helpers/scaffold', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '10', 'parent_id' => '8', 'order' => '852', 'title' => 'Database terminal', 'icon' => 'fa-database', 'uri' => 'helpers/terminal/database', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '11', 'parent_id' => '8', 'order' => '853', 'title' => 'Laravel artisan', 'icon' => 'fa-terminal', 'uri' => 'helpers/terminal/artisan', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '12', 'parent_id' => '8', 'order' => '854', 'title' => 'Routes', 'icon' => 'fa-list-alt', 'uri' => 'helpers/routes', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 公告管理
            ['id' => '100', 'parent_id' => '0', 'order' => '100', 'title' => '網站管理', 'icon' => 'fa-desktop', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '101', 'parent_id' => '100', 'order' => '101', 'title' => '公告管理', 'icon' => 'fa-comments-o', 'uri' => 'website/bulletins', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '102', 'parent_id' => '100', 'order' => '101', 'title' => '經銷商管理', 'icon' => 'fa-comments-o', 'uri' => 'website/dealer', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '103', 'parent_id' => '100', 'order' => '101', 'title' => '網站管理', 'icon' => 'fa-comments-o', 'uri' => 'website/website', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 用戶管理
            ['id' => '120', 'parent_id' => '0', 'order' => '120', 'title' => '用戶管理', 'icon' => 'fa-users', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '121', 'parent_id' => '120', 'order' => '121', 'title' => '用戶管理', 'icon' => 'fa-user', 'uri' => 'user/users', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '122', 'parent_id' => '120', 'order' => '122', 'title' => '用戶投注', 'icon' => 'fa-user-plus', 'uri' => 'user/bettings', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 訂單管理
            ['id' => '140', 'parent_id' => '0', 'order' => '140', 'title' => '訂單管理', 'icon' => 'fa-usd', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '141', 'parent_id' => '140', 'order' => '141', 'title' => '入點紀錄', 'icon' => 'fa-jpy', 'uri' => 'order/enter', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '142', 'parent_id' => '140', 'order' => '142', 'title' => '出點紀錄', 'icon' => 'fa-paypal', 'uri' => 'order/out', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '143', 'parent_id' => '140', 'order' => '143', 'title' => '得分紀錄', 'icon' => 'fa-list', 'uri' => 'order/betting', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 期權管理
            ['id' => '160', 'parent_id' => '0', 'order' => '160', 'title' => '期權管理', 'icon' => 'fa-btc', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '161', 'parent_id' => '160', 'order' => '161', 'title' => '期權種類', 'icon' => 'fa-btc', 'uri' => 'binary/binaries', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '162', 'parent_id' => '160', 'order' => '162', 'title' => '期權幣種', 'icon' => 'fa-cny', 'uri' => 'binary/currencies', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '163', 'parent_id' => '160', 'order' => '163', 'title' => '期權走勢', 'icon' => 'fa-eur', 'uri' => 'binary/trends', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '164', 'parent_id' => '160', 'order' => '164', 'title' => '期權規則', 'icon' => 'fa-gbp', 'uri' => 'binary/rules', 'created_at' => $now_date, 'updated_at' => $now_date],
            // 數據分析
            ['id' => '180', 'parent_id' => '0', 'order' => '180', 'title' => '數據分析', 'icon' => 'fa-line-chart', 'uri' => NULL, 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '181', 'parent_id' => '180', 'order' => '181', 'title' => '投注分析', 'icon' => 'fa-bar-chart', 'uri' => '/', 'created_at' => $now_date, 'updated_at' => $now_date],
                ['id' => '182', 'parent_id' => '180', 'order' => '182', 'title' => 'XX分析', 'icon' => 'fa-area-chart', 'uri' => '/', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
    }
}
