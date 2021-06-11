<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaravelAdmin02RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * 指令:: php artisan db:seed --class=LaravelAdmin02RoleSeeder
     * 
     * @return void
     */
    public function run()
    {
        $now_date = date('Y-m-d h:i:s');

        // 角色
        DB::table('admin_roles')->truncate();
        DB::table('admin_roles')->insert([
            ['id' => '1', 'name' => '系統管理員', 'slug' => 'administrator', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '2', 'name' => '網站管理員', 'slug' => 'webadmin', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_menu')->insert([
            // admin
            ['role_id' => '1', 'menu_id' => '2', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
        DB::table('admin_role_permissions')->truncate();
        DB::table('admin_role_permissions')->insert([
            // admin
            ['role_id' => '1', 'permission_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
        DB::table('admin_role_users')->truncate();
        DB::table('admin_role_users')->insert([
            ['role_id' => '1', 'user_id' => '1', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
    }
}
