<?php

namespace Tests\Feature;

use Database\Factories\EmailModelFactory;
use Faker\Factory as Faker;

class EmailmodelTest extends BaseCase
{
    /** @test */
    public function admin_can_view_all_emailmodels()
    {
        $this->actingAs($this->admin);
        $emailmodel = EmailModelFactory::new()->create();
        $response = $this->get(route('admin.emailmodel.index',['isDataTable'=>1],true));
        $response->assertStatus(200);
        $response->assertSee($emailmodel->name);
        $response->assertSee($emailmodel->description);
    }

    /** @test */
    public function admin_can_view_one_emailmodel()
    {
        $this->actingAs($this->admin);
        $emailmodel = EmailModelFactory::new()->create();
        $response = $this->get(route('admin.emailmodel.show',['emailmodel'=>$emailmodel->id],false));
        $response->assertStatus(200);
        $response->assertSee($emailmodel->name);
        $response->assertSee($emailmodel->description);
    }

    /** @test */
    public function admin_can_add_one_emailmodel()
    {
        $this->actingAs($this->admin);
        $emailmodel = EmailModelFactory::new()->make();
        $response = $this->post(route('admin.emailmodel.store',[],false),$emailmodel->toArray());
        $response->assertRedirect(route('admin.emailmodel.index'));
        $this->assertDatabaseHas('email_models',[
            'name'=>$emailmodel->name,
	            'description'=>$emailmodel->description,
		    ]);

    }


    /** @test */
    public function admin_can_update_one_emailmodel()
    {
        $faker = Faker::create();
        $this->actingAs($this->admin);
        $emailmodel = EmailModelFactory::new()->create();
        $Updated = EmailModelFactory::new()->make();
        $response = $this->put(route('admin.emailmodel.update',[$emailmodel->id],false),$Updated->toArray());
        $response->assertRedirect();
        $this->assertDatabaseHas('email_models',[
            'name'=>$Updated->name,
	            'description'=>$Updated->description,
		    ]);

    }

    /** @test */
    public function admin_can_delete_one_emailmodel()
    {
        $this->actingAs($this->admin);
        $emailmodel = EmailModelFactory::new()->create();
        $response = $this->delete(route('admin.emailmodel.destroy',[$emailmodel->id],false));
        $response->assertRedirect(route('admin.emailmodel.index'));
        $this->assertDatabaseMissing('email_models',[
'name'=>$emailmodel->name,
	'description'=>$emailmodel->description,
	]);

    }
}
