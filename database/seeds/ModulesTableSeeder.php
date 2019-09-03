<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            'module_name' => '管理员管理',
            'pid' => 0,
            'module_desc'=>'管理员管理模块',
            'module_creator'=>'admin',
            'module_accendant'=>'admin',
            'module_version'=>'1.0.0',
            'module_operate'=>'',
            'module_create_time'=>'2019-09-01 10:02:03',
        ]);
        DB::table('modules')->insert([
            'module_name' => '管理员列表',
            'pid' => 1,
            'module_desc'=>'管理员列表管理模块',
            'module_creator'=>'admin',
            'module_accendant'=>'admin',
            'module_version'=>'1.0.0',
            'module_operate'=>'index#列表,show#详情,destroy#删除,update#更新,store#创建',
            'module_create_time'=>'2019-09-01 10:02:03',
        ]);
        DB::table('modules')->insert([
            'module_name' => '角色列表',
            'pid' => 1,
            'module_desc'=>'管理员组列表管理模块',
            'module_creator'=>'admin',
            'module_accendant'=>'admin',
            'module_version'=>'1.0.0',
            'module_operate'=>'index#列表,show#详情,destroy#删除,update#更新,store#创建',
            'module_create_time'=>'2019-09-01 10:02:03',
        ]);
        DB::table('modules')->insert([
            'module_name' => '系统管理',
            'pid' => 0,
            'module_desc'=>'系统管理模块',
            'module_creator'=>'admin',
            'module_accendant'=>'admin',
            'module_version'=>'1.0.0',
            'module_operate'=>'',
            'module_create_time'=>'2019-09-03 15:02:03',
        ]);
        DB::table('modules')->insert([
            'module_name' => '模块列表',
            'pid' => 4,
            'module_desc'=>'模块列表模块',
            'module_creator'=>'admin',
            'module_accendant'=>'admin',
            'module_version'=>'1.0.0',
            'module_operate'=>'index#列表,show#详情,destroy#删除,update#更新,store#创建',
            'module_create_time'=>'2019-09-03 15:02:03',
        ]);
    }
}
