<?php

use Illuminate\Database\Seeder;

class UsergroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usergroups')->insert(['id'=> 1,
                                         'group_title' => "ADMINISTRATOR",
                                         'created_at' => '2017-03-18 00:00:00',
                                         'updated_at' => '2017-03-18 00:00:00']);
        DB::table('usergroups')->insert(['id'=> 2,
                                         'group_title' => "USER",
                                         'created_at' => '2017-03-18 00:00:00',
                                         'updated_at' => '2017-03-18 00:00:00']);
        DB::table('usergroups')->insert(['id'=> 3,
                                         'group_title' => "BOOTH_OPERATOR",
                                         'created_at' => '2017-03-18 00:00:00',
                                         'updated_at' => '2017-03-18 00:00:00']);
        DB::table('usergroups')->insert(['id'=> 4,
                                         'group_title' => "MANAGER",
                                         'created_at' => '2017-03-18 00:00:00',
                                         'updated_at' => '2017-03-18 00:00:00']);
        DB::table('usergroups')->insert(['id'=> 5,
                                         'group_title' => "POLICE",
                                         'created_at' => '2017-03-18 00:00:00',
                                         'updated_at' => '2017-03-18 00:00:00']);

    }
}
