<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->tinyIncrements('id')->commit("模块编号");
            $table->string('module_name',20)->unique()->commit("模块名称");
            $table->tinyInteger('pid')->default(0)->commit("模块父编号");
            $table->string('module_desc',200)->nullable()->commit("模块描述");
            $table->string('module_creator',200)->nullable()->commit("模块添加人");
            $table->string('module_accendant',200)->nullable()->commit("模块维护人");
            $table->string('module_version',200)->nullable()->commit("模块版本");
            $table->string('module_operate',200)->default('index#列表,show#详情,destroy#删除,update#更新,store#创建')->commit("模块可操作的方法");
            $table->timestamp('module_create_time')->nullable()->comment('模块添加时间');
            $table->timestamp('module_update_time')->nullable()->comment('模块更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
    }
}
