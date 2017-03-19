<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(['id' => '1',
                                    'usergroup_id' => '1',
                                    'name' => 'Admin',
                                    'email' => 'admin@mail.com',
                                    'password' => '123456',
                                    'contact_no' => '7509206653',
                                    'wallet_amt' => '0.00',
                                    'is_active' => '1',
                                    'created_at' => '2016-11-15 00:00:00',
                                    'updated_at' => '2016-11-15 00:00:00']);
    }
}
