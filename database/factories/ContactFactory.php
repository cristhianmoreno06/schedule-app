<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'birthdate' => $this->faker->dateTime,
            'address' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
