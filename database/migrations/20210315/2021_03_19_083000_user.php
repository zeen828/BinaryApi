<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class User extends Migration
{
    /**
     * Run the migrations.
     * 
     * php artisan migrate --path="database\migrations\20210315"
     * 
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users'))
        {
            // 增加欄位
            Schema::table('users', function (Blueprint $table)
            {
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)')->after('remember_token');// 添加欄位
                $table->decimal('point', 10, 3)->default(0)->comment('點數')->after('remember_token');// 添加欄位
            });
            DB::statement("ALTER TABLE `users` comment '用戶'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('users'))
        {
            // 移除欄位
            Schema::table('users', function (Blueprint $table)
            {
                $table->dropColumn('point');// 刪除欄位
                $table->dropColumn('status');// 刪除欄位
            });
        }
    }
}
