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
                                    'is_blocked' => '0',
                                    'created_at' => '2016-11-15 00:00:00',
                                    'updated_at' => '2016-11-15 00:00:00']);
        DB::table('users')->insert(['id' => '2',
                                    'usergroup_id' => '3',
                                    'name' => 'Booth Operator',
                                    'email' => 'booth@mail.com',
                                    'password' => '123456',
                                    'contact_no' => '7509206653',
                                    'wallet_amt' => '0.00',
                                    'is_active' => '0',
                                    'is_blocked' => '0',
                                    'created_at' => '2016-11-15 00:00:00',
                                    'updated_at' => '2016-11-15 00:00:00']);
        DB::table('users')->insert(['id' => '3',
                                    'usergroup_id' => '4',
                                    'name' => 'Booth Manager',
                                    'email' => 'manager@mail.com',
                                    'password' => '123456',
                                    'contact_no' => '7509206653',
                                    'wallet_amt' => '0.00',
                                    'is_active' => '0',
                                    'is_blocked' => '0',
                                    'created_at' => '2016-11-15 00:00:00',
                                    'updated_at' => '2016-11-15 00:00:00']);
        DB::table('users')->insert(['id' => '4',
                                    'usergroup_id' => '2',
                                    'name' => 'Driver',
                                    'email' => 'driver@mail.com',
                                    'password' => '123456',
                                    'contact_no' => '7509206653',
                                    'wallet_amt' => '0.00',
                                    'is_active' => '0',
                                    'is_blocked' => '0',
                                    'created_at' => '2016-11-15 00:00:00',
                                    'updated_at' => '2016-11-15 00:00:00']);
    }
}
