<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Website extends Migration
{
    /**
     * Run the migrations.
     * 
     * php artisan migrate --path="database\migrations\20210310"
     * 
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('website'))
        {
            // 建立table
            Schema::create('website', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->string('title', 50)->default('網站')->comment('標題');
                $table->text('description')->nullable()->comment('描述');
                $table->json('keyword')->nullable()->comment('關鍵字');
                $table->string('ga_key', 50)->nullable()->comment('Google Analytics');
                $table->string('domain', 150)->nullable()->comment('域名');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 唯一
                $table->unique('domain', 'unique_domain');
                // 索引
                $table->index(['domain', 'status'], 'query_all');// admin + website
            });
            DB::statement("ALTER TABLE `website` comment '網站配置'");
        }
    }

    /**
     * Reverse the migrations.
     * 
     * php artisan migrate:rollback --path="database\migrations\20210319"
     * 
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('website'))
        {
            // 刪除table
            Schema::dropIfExists('website');
        }
    }
}
