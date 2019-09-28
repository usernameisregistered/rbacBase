<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
            'manager_id'=> '274c57f428feb0f60cdadee060752fd9',
            'manager_name' => 'admin',
            'manager_email' => 'admin@admin.com',
            'manager_phone'=>'18064120655',
            'manager_truename'=>'admin',
            'group_id'=>'dc0c4decd88636fabd9671a864f16324',
            'manager_password'=>md5("12345678"),
            'manager_isSystem'=>true,
            'manager_register_time'=>now()
        ]);
    }
}
