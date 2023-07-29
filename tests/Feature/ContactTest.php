<?php

namespace Tests\Feature;

use Database\Factories\ContactFactory;
use Faker\Factory as Faker;

class ContactTest extends BaseCase
{
    /** @test */
    public function admin_can_view_all_contacts()
    {
        $this->actingAs($this->admin);
        $contact = ContactFactory::new()->create();
        $response = $this->get(route('admin.contact.index',['isDataTable'=>1],true));
        $response->assertStatus(200);
        $response->assertSee($contact->email);
        $response->assertSee($contact->phone);
        $response->assertSee($contact->type);
        $response->assertSee($contact->event_id);
    }

    /** @test */
    public function admin_can_view_one_contact()
    {
        $this->actingAs($this->admin);
        $contact = ContactFactory::new()->create();
        $response = $this->get(route('admin.contact.show',['contact'=>$contact->id],false));
        $response->assertStatus(200);
        $response->assertSee($contact->email);
        $response->assertSee($contact->phone);
        $response->assertSee($contact->type);
        $response->assertSee($contact->event_id);
    }

    /** @test */
    public function admin_can_add_one_contact()
    {
        $this->actingAs($this->admin);
        $contact = ContactFactory::new()->make();
        $response = $this->post(route('admin.contact.store',[],false),$contact->toArray());
        $response->assertRedirect(route('admin.contact.index'));
        $this->assertDatabaseHas('contacts',[
            'email'=>$contact->email,
	            'phone'=>$contact->phone,
	            'type'=>$contact->type,
	            'event_id'=>$contact->event_id,
		    ]);

    }


    /** @test */
    public function admin_can_update_one_contact()
    {
        $faker = Faker::create();
        $this->actingAs($this->admin);
        $contact = ContactFactory::new()->create();
        $Updated = ContactFactory::new()->make();
        $response = $this->put(route('admin.contact.update',[$contact->id],false),$Updated->toArray());
        $response->assertRedirect();
        $this->assertDatabaseHas('contacts',[
            'email'=>$Updated->email,
	            'phone'=>$Updated->phone,
	            'type'=>$Updated->type,
	            'event_id'=>$Updated->event_id,
		    ]);

    }

    /** @test */
    public function admin_can_delete_one_contact()
    {
        $this->actingAs($this->admin);
        $contact = ContactFactory::new()->create();
        $response = $this->delete(route('admin.contact.destroy',[$contact->id],false));
        $response->assertRedirect(route('admin.contact.index'));
        $this->assertDatabaseMissing('contacts',[
'email'=>$contact->email,
	'phone'=>$contact->phone,
	'type'=>$contact->type,
	'event_id'=>$contact->event_id,
	]);

    }
}
