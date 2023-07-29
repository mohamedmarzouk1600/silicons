<?php

namespace Tests\Feature;

use Database\Factories\AdminGroupFactory;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\AdminGroup;
use Faker\Factory as Faker;

class AdmingroupTest extends BaseCase
{
    /** @test */
    public function admin_can_view_all_admingroups()
    {
        $this->actingAs($this->admin);
        $admingroup = AdminGroupFactory::new()->create();
        $response = $this->get(route('admin.admin-groups.index',['isDataTable'=>1],true));
        $response->assertStatus(200);
        $response->assertSee($admingroup->name);
        $response->assertSee($admingroup->status);
    }

    /** @test */
    public function admin_can_view_one_admingroup()
    {
        $this->actingAs($this->admin);
        $admingroup = AdminGroupFactory::new()->create();
        $response = $this->get(route('admin.admin-groups.show',['admin_group'=>$admingroup->id],false));
        $response->assertStatus(200);
        $response->assertSee($admingroup->name);
        $response->assertSee($admingroup->status);
        $response->assertSee($admingroup->home_url);
        $response->assertSee($admingroup->url_index);
    }

    /** @test */
    public function admin_can_add_one_admingroup()
    {
        $this->actingAs($this->admin);
        $admingroup = AdminGroupFactory::new()->make(['user_group'=>UserGroupType::NanoClinic]);
        $response = $this->post(route('admin.admin-groups.store',[],false),
            array_merge($admingroup->toArray(),[
                'permissions'        =>  $this->permissions()->toArray(),
            ])
        );
        $response->assertRedirect(route('admin.admin-groups.index'));
        $this->assertDatabaseHas('admin_groups',[
                'name'=>$admingroup->name,
	            'status'=>$admingroup->status,
	            'home_url'=>$admingroup->home_url,
	            'user_group'=>$admingroup->user_group,
	            'url_index'=>$admingroup->url_index,
		    ]);

    }


    /** @test */
    public function admin_can_update_one_admingroup()
    {
        $this->actingAs($this->admin);
        $admingroup = AdminGroupFactory::new()->create(['user_group'=>UserGroupType::NanoClinic]);
        $Updated = AdminGroupFactory::new()->make(['user_group'=>UserGroupType::NanoClinic]);
        $response = $this->put(route('admin.admin-groups.update',[$admingroup->id],false),
            array_merge($Updated->toArray(),[
                'permissions'        =>  $this->permissions()->toArray(),
            ])
        );
        $response->assertRedirect();
        $this->assertDatabaseHas('admin_groups',[
                'name'=>$Updated->name,
	            'status'=>$Updated->status,
	            'home_url'=>$Updated->home_url,
	            'user_group'=>$Updated->user_group,
	            'url_index'=>$Updated->url_index,
		    ]);

    }

    /** @test */
    public function admin_can_delete_one_admingroup()
    {
        $this->actingAs($this->admin);
        $admingroup = AdminGroupFactory::new()->create();
        $response = $this->delete(route('admin.admin-groups.destroy',[$admingroup->id],false));
        $response->assertRedirect(route('admin.admin-groups.index'));
        $this->assertDatabaseMissing('admin_groups',[
            'name'=>$admingroup->name,
            'status'=>$admingroup->status,
            'home_url'=>$admingroup->home_url,
            'url_index'=>$admingroup->url_index,
	    ]);

    }

    public function permissions(){
        $permissions = collect();
        $perms = recursiveFindArray(require('config/permissions.php'),'permissions');
        foreach($perms as $val){
            foreach($val as $key=>$oneperm){
                $permissions->push($key);
            }
        }
        return $permissions;
    }
}
