<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BinaryCurrencyChart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('binary_currency_chart'))
        {
            // 建立table
            Schema::create('binary_currency_chart', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->integer('binary_currency_id')->comment('期權-幣種id');
                $table->date('date')->comment('時間');
                // 統計(智能開獎用)
                $table->integer('max')->unsigned()->default(0)->comment('買大數量');
                $table->integer('min')->unsigned()->default(0)->comment('買小數量');
                $table->integer('odd')->unsigned()->default(0)->comment('買單數量');
                $table->integer('even')->unsigned()->default(0)->comment('買雙注數量');
                $table->integer('rise')->unsigned()->default(0)->comment('買漲數量');
                $table->integer('fall')->unsigned()->default(0)->comment('買跌數量');
                // 統計(報表用)
                $table->integer('bet_quantity')->unsigned()->default(0)->comment('投注數量');
                $table->decimal('bet_amount', 15, 3)->unsigned()->default(0)->comment('投注金額');
                $table->integer('draw_quantity')->unsigned()->default(0)->comment('中獎數量');
                $table->decimal('draw_amount', 15, 3)->unsigned()->default(0.000)->comment('中獎金額');
                $table->decimal('draw_rate', 5, 2)->unsigned()->default(0.000)->comment('中獎率(100%=100.00)');
                // 統計(K線用)
                $table->string('open')->nullable()->comment('開盤價');
                $table->string('close')->nullable()->comment('收盤價');
                $table->string('high')->nullable()->comment('最高價');
                $table->string('low')->nullable()->comment('最低價');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['binary_currency_id', 'date', 'status'], 'query_all');// admin
            });
            DB::statement("ALTER TABLE `binary_currency_chart` comment '二元期權-圖表'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('binary_currency_chart'))
        {
            // 刪除table
            Schema::dropIfExists('binary_currency_chart');
        }
    }
}
