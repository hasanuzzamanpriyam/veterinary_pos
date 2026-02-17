<?php

namespace Database\Seeders;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a user exists
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create Customers
        if (customer::count() < 5) {
            customer::factory(5)->create();
        }
        $customers = customer::all();

        // Create Suppliers
        if (Supplier::count() < 5) {
            Supplier::factory(5)->create();
        }
        $suppliers = Supplier::all();

        // Create Sales (CustomerLedger) for Today
        foreach ($customers as $customer) {
            CustomerLedger::create([
                'customer_id' => $customer->id,
                'type' => 'sale',
                'date' => Carbon::today(),
                'total_qty' => rand(10, 100),
                'total_price' => rand(1000, 5000),
                'payment' => rand(500, 4000),
                'balance' => rand(100, 1000), // simplistic logic
                'u_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Create Purchases (SupplierLedger) for Today
        foreach ($suppliers as $supplier) {
            SupplierLedger::create([
                'supplier_id' => $supplier->id,
                'type' => 'purchase',
                'date' => Carbon::today(),
                'total_qty' => rand(20, 200),
                'total_price' => rand(5000, 10000),
                'payment' => rand(2000, 8000),
                'balance' => rand(500, 2000), // simplistic logic
                'u_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Dashboard data seeded successfully.');
    }
}
