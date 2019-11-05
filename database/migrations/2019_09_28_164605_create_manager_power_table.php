<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerPowerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_power', function (Blueprint $table) {
            $table->increments('power_id');
            $table->string('module_id',32)->commit("可操作模块名称");
            $table->string('module_power',2000)->default(0)->commit("可允许操作模块方法");
            $table->timestamp('power_create_time')->nullable()->comment('权限添加时间');
            $table->timestamp('power_create_time')->nullable()->comment('权限修改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_power');
    }
}
