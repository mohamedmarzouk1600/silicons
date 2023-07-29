<?php

namespace Tests\Feature;

use Database\Factories\ContactEmailFactory;
use Faker\Factory as Faker;

class ContactemailTest extends BaseCase
{
    /** @test */
    public function admin_can_view_all_contactemails()
    {
        $this->actingAs($this->admin);
        $contactemail = ContactEmailFactory::new()->create();
        $response = $this->get(route('admin.contactemail.index',['isDataTable'=>1],true));
        $response->assertStatus(200);
        $response->assertSee($contactemail->qr);
        $response->assertSee($contactemail->send_email);
        $response->assertSee($contactemail->send_message);
        $response->assertSee($contactemail->scan_qr);
        $response->assertSee($contactemail->email_model_id);
        $response->assertSee($contactemail->contact_id);
    }

    /** @test */
    public function admin_can_view_one_contactemail()
    {
        $this->actingAs($this->admin);
        $contactemail = ContactEmailFactory::new()->create();
        $response = $this->get(route('admin.contactemail.show',['contactemail'=>$contactemail->id],false));
        $response->assertStatus(200);
        $response->assertSee($contactemail->qr);
        $response->assertSee($contactemail->send_email);
        $response->assertSee($contactemail->send_message);
        $response->assertSee($contactemail->scan_qr);
        $response->assertSee($contactemail->email_model_id);
        $response->assertSee($contactemail->contact_id);
    }

    /** @test */
    public function admin_can_add_one_contactemail()
    {
        $this->actingAs($this->admin);
        $contactemail = ContactEmailFactory::new()->make();
        $response = $this->post(route('admin.contactemail.store',[],false),$contactemail->toArray());
        $response->assertRedirect(route('admin.contactemail.index'));
        $this->assertDatabaseHas('contact_emails',[
            'qr'=>$contactemail->qr,
	            'send_email'=>$contactemail->send_email,
	            'send_message'=>$contactemail->send_message,
	            'scan_qr'=>$contactemail->scan_qr,
	            'email_model_id'=>$contactemail->email_model_id,
	            'contact_id'=>$contactemail->contact_id,
		    ]);

    }


    /** @test */
    public function admin_can_update_one_contactemail()
    {
        $faker = Faker::create();
        $this->actingAs($this->admin);
        $contactemail = ContactEmailFactory::new()->create();
        $Updated = ContactEmailFactory::new()->make();
        $response = $this->put(route('admin.contactemail.update',[$contactemail->id],false),$Updated->toArray());
        $response->assertRedirect();
        $this->assertDatabaseHas('contact_emails',[
            'qr'=>$Updated->qr,
	            'send_email'=>$Updated->send_email,
	            'send_message'=>$Updated->send_message,
	            'scan_qr'=>$Updated->scan_qr,
	            'email_model_id'=>$Updated->email_model_id,
	            'contact_id'=>$Updated->contact_id,
		    ]);

    }

    /** @test */
    public function admin_can_delete_one_contactemail()
    {
        $this->actingAs($this->admin);
        $contactemail = ContactEmailFactory::new()->create();
        $response = $this->delete(route('admin.contactemail.destroy',[$contactemail->id],false));
        $response->assertRedirect(route('admin.contactemail.index'));
        $this->assertDatabaseMissing('contact_emails',[
'qr'=>$contactemail->qr,
	'send_email'=>$contactemail->send_email,
	'send_message'=>$contactemail->send_message,
	'scan_qr'=>$contactemail->scan_qr,
	'email_model_id'=>$contactemail->email_model_id,
	'contact_id'=>$contactemail->contact_id,
	]);

    }
}
