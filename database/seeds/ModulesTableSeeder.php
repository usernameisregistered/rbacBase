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
            "module_id"=>md5('管理员管理0'.now()),
            'module_title' => '管理员管理',
            'pid' => 0,
            'module_desc'=>'管理员管理模块',
            'module_create_time'=> now()
        ]);
    }
}
