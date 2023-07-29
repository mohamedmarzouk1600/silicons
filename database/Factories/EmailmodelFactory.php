<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MaxDev\Models\EmailModel;

class EmailModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmailModel::class;

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
        ];
    }
}
