<?php
namespace Database\Seeds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use MaxDev\Enums\UserGroupType;

class AdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        $admins = [
            [
                'fullname' => 'mohamed marzouk',
                'email' => 'mmarzouk@mu.edu.sa',
                'password' => bcrypt('asdasd'),
                'user_group'=>UserGroupType::Admin,
            ],
        ];
        array_map(function($admin){
            (new \MaxDev\Models\Admin())->fill(array_merge($admin,[
                'password' => bcrypt('asdasd'),
                'status' => '1',
                'remember_token' => NULL,
            ]))->save();
        },$admins);


    }
}
