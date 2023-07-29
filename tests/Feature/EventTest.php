<?php

namespace Tests\Feature;

use Database\Factories\EventFactory;
use Faker\Factory as Faker;

class EventTest extends BaseCase
{
    /** @test */
    public function admin_can_view_all_events()
    {
        $this->actingAs($this->admin);
        $event = EventFactory::new()->create();
        $response = $this->get(route('admin.event.index',['isDataTable'=>1],true));
        $response->assertStatus(200);
        $response->assertSee($event->name);
        $response->assertSee($event->description);
        $response->assertSee($event->from_date);
        $response->assertSee($event->to_date);
        $response->assertSee($event->lat);
        $response->assertSee($event->lng);
    }

    /** @test */
    public function admin_can_view_one_event()
    {
        $this->actingAs($this->admin);
        $event = EventFactory::new()->create();
        $response = $this->get(route('admin.event.show',['event'=>$event->id],false));
        $response->assertStatus(200);
        $response->assertSee($event->name);
        $response->assertSee($event->description);
        $response->assertSee($event->from_date);
        $response->assertSee($event->to_date);
        $response->assertSee($event->lat);
        $response->assertSee($event->lng);
    }

    /** @test */
    public function admin_can_add_one_event()
    {
        $this->actingAs($this->admin);
        $event = EventFactory::new()->make();
        $response = $this->post(route('admin.event.store',[],false),$event->toArray());
        $response->assertRedirect(route('admin.event.index'));
        $this->assertDatabaseHas('events',[
            'name'=>$event->name,
	            'description'=>$event->description,
	            'from_date'=>$event->from_date,
	            'to_date'=>$event->to_date,
	            'lat'=>$event->lat,
	            'lng'=>$event->lng,
		    ]);

    }


    /** @test */
    public function admin_can_update_one_event()
    {
        $faker = Faker::create();
        $this->actingAs($this->admin);
        $event = EventFactory::new()->create();
        $Updated = EventFactory::new()->make();
        $response = $this->put(route('admin.event.update',[$event->id],false),$Updated->toArray());
        $response->assertRedirect();
        $this->assertDatabaseHas('events',[
            'name'=>$Updated->name,
	            'description'=>$Updated->description,
	            'from_date'=>$Updated->from_date,
	            'to_date'=>$Updated->to_date,
	            'lat'=>$Updated->lat,
	            'lng'=>$Updated->lng,
		    ]);

    }

    /** @test */
    public function admin_can_delete_one_event()
    {
        $this->actingAs($this->admin);
        $event = EventFactory::new()->create();
        $response = $this->delete(route('admin.event.destroy',[$event->id],false));
        $response->assertRedirect(route('admin.event.index'));
        $this->assertDatabaseMissing('events',[
'name'=>$event->name,
	'description'=>$event->description,
	'from_date'=>$event->from_date,
	'to_date'=>$event->to_date,
	'lat'=>$event->lat,
	'lng'=>$event->lng,
	]);

    }
}
