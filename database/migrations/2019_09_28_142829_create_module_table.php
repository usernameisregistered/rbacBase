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
            $table->char('module_id',32)->commit("模块编号");
            $table->string('module_title',20)->unique()->commit("模块名称");
            $table->string('pid',32)->default(0)->commit("模块父编号");
            $table->string('module_desc',200)->nullable()->commit("模块描述");
            $table->string('module_icon',200)->nullable()->commit("模块图标");
            $table->string('module_path',200)->nullable()->commit("模块路径");
            $table->string('module_operate',2000)->nullable()->commit("模块可操作的方法");
            $table->timestamp('module_create_time')->nullable()->comment('模块添加时间');
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
