<?php

use Illuminate\Database\Seeder;

class ManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('managers')->insert([
            'id'=> 1000,
            'manager_name' => 'admin',
            'manager_email' => 'admin@admin.com',
            'manager_phone'=>'18064120655',
            'manager_truename'=>'admin',
            'manager_group'=>'100',
            'manager_password'=>md5("12345678"),
            'manager_register_time'=>now()
        ]);
        DB::table('managers')->insert([
            'manager_name' => 'test',
            'manager_email' => 'test@test.com',
            'manager_phone'=>'18064120653',
            'manager_truename'=>'test',
            'manager_group'=>'101',
            'manager_password'=>md5("12345678"),
            'manager_register_time'=>now()
        ]);DB::table('managers')->insert([
            'manager_name' => 'demo',
            'manager_email' => 'demo@demo.com',
            'manager_phone'=>'18064120652',
            'manager_truename'=>'demo',
            'manager_group'=>'101',
            'manager_password'=>md5("12345678"),
            'manager_register_time'=>now()
        ]);
    }
}
