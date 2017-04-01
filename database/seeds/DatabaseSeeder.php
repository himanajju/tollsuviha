<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    
    private $tables = array(
        'usergroups',
        'users',
        'vehicles',
        'toll_details'
    );
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $this->cleanDatabase();
        $this->call(UsergroupsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(VehiclesTableSeeder::class);
        $this->call(TollDetailsTableSeeder::class);
    }

    
    private function cleanDatabase()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}