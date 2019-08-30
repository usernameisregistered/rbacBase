<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->mediumIncrements('id')->comment('用户编号');
            $table->string('user_name',50)->comment('用户昵称');
            $table->string('user_email',50)->unique()->nullable()->comment('用户邮箱');
            $table->char('user_phone',11)->unique()->nullable()->comment('用户手机');
            $table->string('user_truename',20)->nullable()->comment('用户真实姓名');
            $table->boolean('user_gender')->default(true)->comment('用户性别 true:男 false:女');
            $table->tinyInteger('user_group')->default(1)->comment('用户所属组');
            $table->boolean('user_isenabled')->default(true)->comment('用户是否启用 true 启用 0 禁用');
            $table->boolean('user_isdelete')->default(false)->comment('用户是否删除 true 删除 0 未删除');
            $table->string('user_disabled_description',200)->nullable()->comment('用户禁用原因');
            $table->timestamp('user_disabled_time')->nullable()->comment('用户禁用时间');
            $table->timestamp('user_lastlogin_time')->nullable()->comment('用户上一次登录时间');
            $table->string('user_lastlogin_ip',15)->nullable()->comment('用户上一次登录ip');
            $table->string('user_password',32)->comment('用户密码');
            $table->string('user_token',100)->nullable()->comment('用户token');
            $table->timestamp('user_register_time')->useCurrent()->comment('用户注册时间');
            $table->timestamp('user_update_time')->nullable()->comment('用户更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
