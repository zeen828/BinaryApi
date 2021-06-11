<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class WebsiteDealer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('website_dealer'))
        {
            // 建立table
            Schema::create('website_dealer', function (Blueprint $table) {
                $table->string('iv', 20)->comment('IV');
                $table->string('name', 50)->comment('名稱');
                $table->string('key', 50)->nullable()->comment('KEY');
                $table->ipAddress('ip')->nullable()->comment('IP');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 唯一
                $table->unique('iv', 'uniqueIV');
                // 索引
                $table->index(['iv', 'key', 'status'], 'query_all');// admin + website
            });
            DB::statement("ALTER TABLE `website_dealer` comment '經銷商'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('website_dealer'))
        {
            // 刪除table
            Schema::dropIfExists('website_dealer');
        }
    }
}
