<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PatientFactory extends Factory
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
            'unify_active' => null,
            'unify_status' => null,
            'unify_process' => rand(0,1),
            'unify_task_status' => null,
            'full_name' => $full_name,
            'last_name' => $last_name,
            'first_name' => $first_name,
            'dob' => fake()->date(),
            'height' => null,
            'weight' => null,
            'gender' => $gender,
            'insurance_coverages' => null,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'street' => fake()->streetAddress(),
            'apt' => null,
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip' => fake()->postcode(),
            'route' => null,
            'sub_r' => null,
            'supplies' => null,
            'products' => null,
            'note' => null,
            'unify_data' => null,
            'doctor_id' => array_rand(Doctor::all()->pluck('id')->toArray()),
            'doctor_status' => 0,
            'doctor_comment' => null,
            'service_dates' => null,
            'need_improve' => 0,
            'unify_inactive_at' => null,
            'unify_deleted' => 0,
            'unify_deleted_at' => null,
            'unify_deleted_by' => null,
            'sale_user' => array_rand(User::all()->pluck('id')->toArray()),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }
}
