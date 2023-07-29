<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MaxDev\Enums\Status;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\AdminGroup;

class AdminGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdminGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'          =>          $this->faker->name,
            'status'        =>          $this->faker->randomElement(Status::getValues()),
            'user_group'    =>          UserGroupType::Admin,
            'home_url'      =>          'view-admin',
        ];
    }
}
