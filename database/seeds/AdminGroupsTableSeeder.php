<?php
namespace Database\Seeds;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\AdminGroup;

class AdminGroupsTableSeeder extends Seeder
{

    /**
     * Auto Generated Seed File
     * @return void
     * @throws \ReflectionException
     */
    public function run(): void
    {
        DB::table('admin_groups')->delete();

        array_map(function($role) {
            $adminGroup = new AdminGroup();


            $adminGroup->fill([
                'name'          => UserGroupType::getName($role),
                'home_url'      => 'admin-dashboard',
                'url_index'     => NULL,
                'user_group'    => $role,
                'status'        => '1',
                'created_at'    => now(),
                'updated_at'    => now()
            ])->save();
        }, UserGroupType::getValues());

    }
}
