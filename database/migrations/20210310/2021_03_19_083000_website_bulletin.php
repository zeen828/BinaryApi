<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class WebsiteBulletin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('website_bulletin'))
        {
            // 建立table
            Schema::create('website_bulletin', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->string('title', 100)->comment('標題');
                $table->text('content')->comment('內容');
                $table->string('img')->nullable()->comment('圖片');
                $table->timestamp('start_at')->nullable()->comment('開始時間');
                $table->timestamp('end_at')->nullable()->comment('結束時間');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['title', 'start_at', 'end_at', 'status'], 'query_all');// admin + website
            });
            DB::statement("ALTER TABLE `website_bulletin` comment '公告'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('website_bulletin'))
        {
            // 刪除table
            Schema::dropIfExists('website_bulletin');
        }
    }
}
