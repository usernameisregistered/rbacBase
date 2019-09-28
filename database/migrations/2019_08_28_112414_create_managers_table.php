<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->string('manager_id',32)->primary()->comment('管理员编号');
            $table->string('manager_name',50)->unique()->comment('管理员昵称');
            $table->string('manager_email',50)->unique()->comment('管理员邮箱');
            $table->char('manager_phone',11)->unique()->comment('管理员手机');
            $table->string('manager_truename',20)->comment('管理员真实姓名');
            $table->string('manager_password',32)->comment('管理员密码');
            $table->boolean('manager_isSystem')->default(false)->comment('系统是系统内置 true 内置 0 外置');
            $table->string('group_id',32)->comment('管理员所属角色');
            $table->boolean('manager_isEnabled')->default(true)->comment('管理员是否启用 true 启用 0 禁用');
            $table->string('manager_disabled_description',200)->nullable()->comment('管理员禁用原因');
            $table->timestamp('manager_disabled_time')->nullable()->comment('管理员禁用时间');
            $table->timestamp('manager_lastlogin_time')->nullable()->comment('管理员上一次登录时间');
            $table->string('manager_lastlogin_ip',15)->nullable()->comment('管理员上一次登录ip');
            $table->timestamp('manager_register_time')->default(now())->comment('管理员注册时间');
            $table->timestamp('manager_update_time')->nullable()->comment('管理员信息更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}
