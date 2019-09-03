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
            $table->tinyIncrements('id')->commit("管理员组编号");
            $table->string('group_name',20)->unique()->commit("管理员组昵称");
            $table->string('group_desc',200)->commit("管理员组描述");
            $table->boolean('group_isenabled')->default(true)->comment('管理员组否启用 true 启用 0 禁用');
            $table->string('group_disabled_description',200)->nullable()->comment('管理员组禁用原因');
            $table->timestamp('group_disabled_time')->nullable()->comment('管理员组禁用时间');
            $table->timestamp('group_create_time')->nullable()->comment('管理员组添加时间');
            $table->timestamp('group_update_time')->nullable()->comment('管理员组更新时间');
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
