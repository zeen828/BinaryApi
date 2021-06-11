<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserBetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_betting'))
        {
            // 建立table
            Schema::create('user_betting', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->integer('user_id')->comment('用戶id');
                $table->integer('binary_currency_id')->comment('期權-幣種id');
                $table->integer('binary_currency_trend_id')->comment('期權-幣種-走勢id');
                $table->integer('binary_rule_currency_id')->comment('期權-規則-幣種id');
                $table->string('binary_rule_currency_value', 30)->comment('期權-規則-幣種數值');
                $table->integer('quantity')->unsigned()->comment('數量');
                $table->decimal('amount', 10, 3)->unsigned()->comment('投注金額');
                $table->decimal('profit', 15, 3)->unsigned()->comment('預期利潤');
                $table->tinyInteger('win_sys')->default(0)->comment('輸贏系統(0:未開,1:贏,-1:輸)');
                $table->tinyInteger('win_user')->default(0)->comment('輸贏用戶(0:未開,1:贏,-1:輸)');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['user_id', 'binary_currency_id', 'win_sys', 'status'], 'query_admin');// admin
                $table->index(['user_id', 'binary_currency_id', 'win_user', 'status'], 'query_website');// website
            });
            DB::statement("ALTER TABLE `user_betting` comment '會員-投注紀錄'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('user_betting'))
        {
            // 刪除table
            Schema::dropIfExists('user_betting');
        }
    }
}
