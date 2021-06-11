<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BinaryCurrencyTrend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('binary_currency_trend'))
        {
            // 建立table
            Schema::create('binary_currency_trend', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->integer('binary_currency_id')->comment('期權-幣種id');
                $table->string('period', 30)->comment('期別');
                // 時間
                $table->timestamp('bet_at')->nullable()->comment('開始投注時間');
                $table->timestamp('stop_at')->nullable()->comment('停止投注時間');
                $table->timestamp('draw_at')->nullable()->comment('開獎時間');
                // 走勢
                $table->string('draw')->nullable()->comment('開獎');
                $table->string('trend_before')->nullable()->comment('上一期走勢');
                $table->string('trend_api')->nullable()->comment('API走勢');
                $table->string('trend')->nullable()->comment('走勢(API抓資料將開獎加在小數點後)');
                $table->json('forecast')->nullable()->comment('預報(筆數)');
                $table->json('draw_rule_json')->nullable()->comment('中獎規則(JSON)');
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
                $table->tinyInteger('redeem')->unsigned()->default(0)->comment('兌獎0:未兌1:已兌');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['binary_currency_id', 'draw_at', 'status'], 'query_all');// 一般查詢
                $table->index(['period', 'status'], 'query_period');// 期別查詢
                $table->index(['redeem', 'status'], 'query_redeem');// 兌獎查詢(未對獎redeem=0，已開獎status=1)
                $table->index(['binary_currency_id', 'bet_at', 'stop_at', 'draw_at', 'redeem', 'status'], 'query_betting');// 下注查詢(現在時間是下注到停止下注的區間，未開獎開獎status=0)
            });
            DB::statement("ALTER TABLE `binary_currency_trend` comment '二元期權-幣種-走勢'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('binary_currency_trend'))
        {
            // 刪除table
            Schema::dropIfExists('binary_currency_trend');
        }
    }
}
