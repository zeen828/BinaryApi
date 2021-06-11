<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BinaryRuleType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('binary_rule_type'))
        {
            // 建立table
            Schema::create('binary_rule_type', function (Blueprint $table) {
                $table->increments('id')->comment('ID');
                $table->string('name', 50)->comment('名稱');
                $table->text('description')->nullable()->comment('描述');
                $table->tinyInteger('sort')->default(0)->comment('排序');
                // 狀態
                $table->tinyInteger('status')->default(0)->comment('狀態(0:停用,1:啟用)');
                $table->timestamp('created_at')->nullable()->comment('建立時間');
                $table->timestamp('updated_at')->nullable()->comment('更新時間');
                // 索引
                $table->index(['sort', 'status'], 'query_all');// admin
            });
            DB::statement("ALTER TABLE `binary_rule_type` comment '二元期權-規則-類型'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('binary_rule_type'))
        {
            // 刪除table
            Schema::dropIfExists('binary_rule_type');
        }
    }
}
