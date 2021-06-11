<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('order'))
        {
            // 建立table
            Schema::create('order', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->string('sn', 100)->comment('序號');
                $table->integer('user_id')->comment('用戶id');
                $table->string('order_sn', 100)->nullable()->comment('對接序號紀錄');
                $table->enum('event', ['billing', 'payment', 'deposit', 'score'])->default('billing')->comment('事件(billing:結算,payment:付款,deposit:存款,score:得分)');
                $table->decimal('point', 10, 3)->default(0)->comment('點數');
                $table->text('remarks')->nullable()->comment('備註');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['sn', 'user_id', 'order_sn', 'event', 'status'], 'query_all');// admin + website
            });
            DB::statement("ALTER TABLE `order` comment '訂單'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('order'))
        {
            // 刪除table
            Schema::dropIfExists('order');
        }
    }
}
