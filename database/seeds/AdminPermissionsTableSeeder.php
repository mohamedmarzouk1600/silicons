<?php
namespace Database\Seeds;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\AdminGroup;

class AdminPermissionsTableSeeder extends Seeder
{
    /**
     * Auto Generated Seed File.
     * @return void
     */
    public function run(): void
    {
        $superAdminGroup = AdminGroup::where('user_group', UserGroupType::Admin)->first();
        DB::table('admin_permissions')->where('admin_group_id', $superAdminGroup->id)->delete();

        DB::table('admin_permissions')->insert([
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.dashboard'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admins.index'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admins.create'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admins.store'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admins.show'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admins.edit'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admins.update'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admins.destroy'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.index'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.index'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.create'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.store'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.show'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.edit'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.update'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.admin-groups.destroy'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.logs.index'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.logs.show'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.settings.index'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.settings.create'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.settings.store'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.settings.show'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.settings.edit'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.settings.update'
            ],
            [
                'admin_group_id'    => $superAdminGroup->id,
                'route_name'        => 'admin.settings.destroy'
            ],
        ]);

    }
}
