<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_groups', function (Blueprint $table) {
            $table->string('group_id',32)->primary()->commit("角色编号");
            $table->string('group_name',20)->unique()->commit("角色昵称");
            $table->string('group_desc',200)->commit("角色描述");
            $table->boolean('group_isSystem')->default(false)->comment('角色内置 true 内置 0 外置');
            $table->boolean('group_isenabled')->default(true)->comment('角色状态 true 启用 0 禁用');
            $table->string('group_disabled_description',200)->nullable()->comment('角色禁用原因');
            $table->timestamp('group_disabled_time')->nullable()->comment('角色禁用时间');
            $table->timestamp('group_create_time')->nullable()->comment('角色添加时间');
            $table->timestamp('group_update_time')->nullable()->comment('角色更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_groups');
    }
}
