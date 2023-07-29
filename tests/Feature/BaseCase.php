<?php

namespace Tests\Feature;

use Database\Factories\AdminFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MaxDev\Models\AdminPermission;
use Tests\TestCase;
use Faker\Factory as Faker;

class BaseCase extends TestCase
{
    use RefreshDatabase;

    public $admin;
    public $media;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CreateAdmin();
        $faker = Faker::create();
//        $faker->addProvider(new ImagesGeneratorProvider($faker));
//        $this->media = $faker->image;
    }

    function regenerateMedia(){
        $faker = Faker::create();
//        $faker->addProvider(new ImagesGeneratorProvider($faker));
//        $this->media = $faker->image;
    }

    protected function CreateAdmin(){
        $this->admin = AdminFactory::new()->create();
        $this->admin->permissions()->insert($this->getAllPermissions($this->admin->adminGroup->id)->toArray());
    }

    protected function getAllPermissions($groupId){
        $permissions = collect();
        $perms = recursiveFindArray(require('config/permissions.php'),'permissions');
        foreach($perms as $val){
            foreach($val as $key=>$oneperm){
                foreach($oneperm as $Apermission){
                    $permissions->push(new AdminPermission(['admin_group_id'=>$groupId,'route_name'=>$Apermission]));
                }
            }
        }
        return $permissions;
    }

}
