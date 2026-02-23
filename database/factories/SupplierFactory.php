<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company(),
            'owner_name' => $this->faker->name(),
            'officer_name' => $this->faker->name(),
            'dealer_area' => $this->faker->state(),
            'dealer_code' => $this->faker->numerify('DL####'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->numerify('01#########'),
            'address' => $this->faker->address(),
            'condition' => $this->faker->randomElement(['Active', 'Inactive', 'Pending']),
            'security' => $this->faker->randomElement(['Valid', 'Invalid', 'Pending']),
            'ledger_page' => $this->faker->numerify('####'),
            'price_group' => $this->faker->randomElement(['Wholesale', 'Retail', 'Standard']),
            'credit_limit' => $this->faker->randomNumber(5),
            'balance' => $this->faker->randomFloat(2, 0, 100000),
            'starting_date' => $this->faker->date('Y-m-d', '-1 year'),
            'photo' => null,
        ];
    }
}
