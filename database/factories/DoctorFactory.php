<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $last_name = fake()->lastName();
        $first_name = fake()->firstName();
        $full_name = $last_name . ' ' . $first_name;
        $gender = rand(0, 1);
        return [
            'full_name' => $full_name,
            'last_name' => $last_name,
            'first_name' => $first_name,
            'dob' => fake()->date('Y-m-d', '1990-01-01'),
            'gender' => $gender,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip' => fake()->postcode(),
            'note' => null,
            'status' => null,
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
        ];
    }

}
