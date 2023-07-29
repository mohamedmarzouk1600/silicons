<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $this->call(\Database\Seeds\AdminsTableSeeder::class);
        $this->call(\Database\Seeds\AdminGroupsTableSeeder::class);
        $this->call(\Database\Seeds\AdminPermissionsTableSeeder::class);
    }
}
