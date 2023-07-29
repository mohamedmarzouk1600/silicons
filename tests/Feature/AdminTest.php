<?php

namespace Tests\Feature;

use Database\Factories\AdminFactory;
use Faker\Factory as Faker;

class AdminTest extends BaseCase
{
    /** @test */
    public function admin_can_view_all_admins()
    {
        $this->actingAs($this->admin);
        $user = AdminFactory::new()->create();
        $response = $this->get(route('admin.admins.index',['isDataTable'=>1],true));
        $response->assertStatus(200);
        $response->assertSee($user->fullname);
        $response->assertSee($user->email);
    }

    /** @test */
    public function admin_can_view_one_admin()
    {
        $this->actingAs($this->admin);
        $user = AdminFactory::new()->create();
        $response = $this->get(route('admin.admins.show',['user'=>$user->id],false));
        $response->assertStatus(200);
        $response->assertSee($user->fullname);
        $response->assertSee($user->email);
        $response->assertSee($user->admin_group_id);
        $response->assertSee($user->user_group);
        $response->assertSee($user->status);
    }

    /** @test */
    public function admin_can_add_one_admin()
    {
        $this->actingAs($this->admin);
        $user = AdminFactory::new()->make();
        $response = $this->post(route('admin.admins.store',[],false),
        array_merge($user->toArray(),[
            'password'                      =>      '123456',
            'password_confirmation'         =>      '123456',
        ])
        );
        $response->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseHas('users',[
                'fullname'=>$user->fullname,
	            'email'=>$user->email,
	            'user_group'=>$user->user_group,
	            'status'=>$user->status,
		    ]);

    }


    /** @test */
    public function admin_can_update_one_admin()
    {
        $faker = Faker::create();
        $this->actingAs($this->admin);
        $user = AdminFactory::new()->create();
        $Updated = AdminFactory::new()->make();
        $response = $this->put(route('admin.admins.update',[$user->id],false),$Updated->toArray());
        $response->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseHas('users',[
                'fullname'=>$Updated->fullname,
	            'email'=>$Updated->email,
		    ]);

    }

    /** @test */
    public function admin_can_delete_one_admin()
    {
        $this->actingAs($this->admin);
        $user = AdminFactory::new()->create();
        $response = $this->delete(route('admin.admins.destroy',[$user->id],false));
        $response->assertRedirect(route('admin.admins.index'));
        $this->assertDatabaseMissing('users',[
                'fullname'=>$user->fullname,
                'email'=>$user->email,
                'user_group'=>$user->user_group,
                'status'=>$user->status,
	]);

    }
}
