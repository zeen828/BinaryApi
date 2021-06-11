<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BinaryRuleCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('binary_rule_currency'))
        {
            // 建立table
            Schema::create('binary_rule_currency', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->integer('binary_currency_id')->comment('期權-幣種id');
                $table->integer('binary_rule_type_id')->comment('期權-規則id');
                $table->string('name', 50)->comment('名稱');
                $table->json('rule_json')->nullable()->comment('規則JSON');
                $table->json('bet_json')->nullable()->comment('投注選項值JSON');
                $table->decimal('odds', 10, 3)->default(1.000)->comment('賠率');
                $table->tinyInteger('sort')->default(0)->comment('排序');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['binary_currency_id', 'sort', 'status'], 'query_all');// admin
            });
            DB::statement("ALTER TABLE `binary_rule_currency` comment '二元期權-規則-幣種'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('binary_rule_currency'))
        {
            // 刪除table
            Schema::dropIfExists('binary_rule_currency');
        }
    }
}
