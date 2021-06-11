<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BinaryCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('binary_currency'))
        {
            // 建立table
            Schema::create('binary_currency', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->integer('binary_id')->comment('貨幣別id');
                $table->string('binary_name', 30)->comment('期權名稱');
                $table->string('binary_code', 30)->comment('期權代號');
                $table->string('currency_name', 30)->comment('幣別名稱');
                $table->string('currency_code', 20)->nullable()->comment('幣別代號');
                // 走勢
                $table->json('trend_data_json')->nullable()->comment('走勢用資料(JSON)');
                $table->tinyInteger('trend_digits')->default(0)->comment('位數');
                $table->tinyInteger('trend_repeat')->default(0)->comment('重複號碼0:不重複1:可重複');
                // 預報
                $table->json('forecast_data_json')->nullable()->comment('預報用資料(特別號)(JSON)');
                $table->tinyInteger('forecast_digits')->default(0)->comment('位數');
                $table->tinyInteger('forecast_repeat')->default(0)->comment('重複號碼0:不重複1:可重複');
                // 運作時間
                $table->string('week')->default('[]')->comment('營運週期[1,2,3,4,5,6,7]');
                $table->time('start_t')->nullable()->comment('開始時間');
                $table->time('end_t')->nullable()->comment('結束時間23:59:59程式處理跨日');
                $table->integer('stop_enter')->default(60)->comment('投注截止時間(前幾秒)');
                $table->integer('repeat')->default(0)->comment('間格0只開一次不循環(幾秒一間格)');
                $table->tinyInteger('reservation')->default(1)->comment('預開獎0:正常開1:預開');
                $table->decimal('win_rate', 5, 2)->default(0.40)->comment('贏率(100%=100.00)');
                $table->tinyInteger('sort')->default(0)->comment('排序');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['binary_id', 'sort', 'status'], 'query_all');// admin
            });
            DB::statement("ALTER TABLE `binary_currency` comment '二元期權-幣種'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('binary_currency'))
        {
            // 刪除table
            Schema::dropIfExists('binary_currency');
        }
    }
}
