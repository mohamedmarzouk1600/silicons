<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MaxDev\Enums\Language;
use MaxDev\Enums\Status;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\Admin;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Admin $admin) {
            //
        })->afterCreating(function (Admin $admin) {
            // create group to match role
            AdminGroupFactory::new()->create(['user_group'=>$admin->user_group]);
        });
    }


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fullname' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'user_group' => \MaxDev\Enums\UserGroupType::Admin,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'status'=>$this->faker->randomElement(Status::getValues()),
            'remember_token' => Str::random(10),
            'language'  =>  $this->faker->randomElement(Language::getValues()),
        ];
    }

}
