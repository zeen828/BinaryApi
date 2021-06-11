<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaravelAdmin01UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * 指令:: php artisan db:seed --class=LaravelAdmin01UserSeeder
     * 
     * @return void
     */
    public function run()
    {
        $now_date = date('Y-m-d h:i:s');

        // 管理員
        DB::table('admin_users')->truncate();
        DB::table('admin_users')->insert([
            ['id' => '1', 'username' => 'admin', 'password' => '$2y$10$hR.BLlFF.GLIp70.VMAY1ucH63a9J4KPSM3dlwodAY2YVpuHIhPpC', 'name' => '網站管理員', 'created_at' => $now_date, 'updated_at' => $now_date],
            ['id' => '2', 'username' => 'webadmin', 'password' => '$2y$10$NHdn7yiXOcp04Cyit82loOU8qICFYrOTx8qhyuVw5hlq3/lNkb7Qe', 'name' => '網站管理員', 'created_at' => $now_date, 'updated_at' => $now_date],
        ]);
        // 權限
        DB::table('admin_user_permissions')->truncate();
    }
}
