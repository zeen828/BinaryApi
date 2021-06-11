<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Binary extends Migration
{
    /**
     * Run the migrations.
     * 
     * php artisan migrate --path="database\migrations\20210320"
     * 
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('binary'))
        {
            // 建立table
            Schema::create('binary', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->string('name', 50)->comment('期權名稱');
                $table->string('code', 20)->nullable()->comment('期權代號');
                $table->string('logo', 255)->nullable()->comment('期權Logo');
                $table->text('description')->nullable()->comment('期權描述');
                $table->text('description_zh')->nullable()->comment('期權描述(中文)');
                $table->string('website')->nullable()->comment('期權網站');
                $table->text('currency')->nullable()->comment('幣種代號');
                $table->tinyInteger('sort')->default(0)->comment('排序');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['code', 'sort', 'status'], 'query_all');// admin
            });
            DB::statement("ALTER TABLE `binary` comment '二元期權'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('binary'))
        {
            // 刪除table
            Schema::dropIfExists('binary');
        }
    }
}
