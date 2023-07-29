<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MaxDev\Models\ContactEmail;

class ContactEmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactEmail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        "qr"          =>          $this->faker->name,
        "send_email"          =>          $this->faker->name,
        "send_message"          =>          $this->faker->name,
        "scan_qr"          =>          $this->faker->name,
        "email_model_id"          =>          Database\Factories\EmailModelFactory::new()->create([]),
        "contact_id"          =>          Database\Factories\ContactFactory::new()->create([]),
        ];
    }
}
