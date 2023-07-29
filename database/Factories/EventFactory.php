<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MaxDev\Models\Event;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        "name"          =>          $this->faker->name,
        "description"          =>          $this->faker->name,
        "from_date"          =>          $this->faker->name,
        "to_date"          =>          $this->faker->name,
        "lat"          =>          $this->faker->name,
        "lng"          =>          $this->faker->name,
        ];
    }
}
