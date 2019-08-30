<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->tinyIncrements('id')->commit("用户组编号");
            $table->string('group_name',20)->commit("用户组昵称");
            $table->string('group_desc',200)->commit("用户组描述");
            $table->boolean('group_isenabled')->default(true)->comment('用户是否启用 true 启用 0 禁用');
            $table->timestamp('group_create_time')->useCurrent()->comment('用户组添加时间');
            $table->timestamp('group_update_time')->nullable()->comment('用户组更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
    }
}
