<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'father_name' => $this->faker->name(),
            'company_name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->numerify('01#########'),
            'address' => $this->faker->address(),
            'nid' => $this->faker->numerify('###############'),
            'birthday' => $this->faker->date('Y-m-d', '-50 years'),
            'ledger_page' => $this->faker->numerify('####'),
            'type' => $this->faker->randomElement(['Retail', 'Wholesale', 'Corporate']),
            'price_group_id' => null,
            'security' => $this->faker->randomElement(['Valid', 'Invalid', 'Pending']),
            'credit_limit' => $this->faker->randomFloat(2, 5000, 100000),
            'balance' => $this->faker->randomFloat(2, 0, 50000),
            'starting_date' => $this->faker->date('Y-m-d', '-1 year'),
            'photo' => null,
            'guarantor_name' => $this->faker->name(),
            'guarantor_company_name' => $this->faker->company(),
            'guarantor_birthday' => $this->faker->date('Y-m-d', '-50 years'),
            'guarantor_mobile' => $this->faker->numerify('01#########'),
            'guarantor_father_name' => $this->faker->name(),
            'guarantor_phone' => $this->faker->phoneNumber(),
            'guarantor_email' => $this->faker->safeEmail(),
            'guarantor_address' => $this->faker->address(),
            'guarantor_security' => $this->faker->randomElement(['Valid', 'Invalid', 'Pending']),
            'guarantor_nid' => $this->faker->numerify('###############'),
            'guarantor_remarks' => $this->faker->sentence(),
            'guarantor_photo' => null,
        ];
    }
}
