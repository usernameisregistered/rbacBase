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
            'group_id'=>"dc0c4decd88636fabd9671a864f16324",
            'group_name' => '超级管理员',
            'group_desc' => '具有至高无上的权利',
            "group_isSystem"=>1,
            'group_create_time'=>now()
        ]);
    }
}
