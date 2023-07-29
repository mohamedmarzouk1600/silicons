<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MaxDev\Models\Contact;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        "email"          =>          $this->faker->name,
        "phone"          =>          $this->faker->name,
        "type"          =>          $this->faker->name,
        "event_id"          =>          Database\Factories\EventFactory::new()->create([]),
        ];
    }
}
