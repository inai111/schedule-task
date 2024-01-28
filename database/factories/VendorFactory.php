<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();
        return [
            'name' => $name,
            'address' => fake()->address(),
            'phone_number' => fake()->unique()->phoneNumber(),
            'bank_name' => fake()->lastName(),
            'bank_account_name' => $name,
            'bank_account_number' => fake()->unique()->numberBetween(9999,99999999),
        ];
    }
}
