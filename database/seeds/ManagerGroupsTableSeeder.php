<?php

use Illuminate\Database\Seeder;

class ManagerGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('manager_groups')->insert([
            'group_name' => '超级管理员',
            'group_desc' => '具有至高无上的权利',
            'group_create_time'=>now()
        ]);
        DB::table('manager_groups')->insert([
            'group_name' => '普通管理员',
            'group_desc' => '具有对自己添加的数据操作的权利',
            'group_create_time'=>now()
        ]);
    }
}
