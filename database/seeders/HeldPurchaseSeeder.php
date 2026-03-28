<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\HeldPurchase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Store;
use Carbon\Carbon;

class HeldPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $supplier = Supplier::first();
        $warehouse = Warehouse::first();
        $store = Store::first();

        // If core missing models, skip creation gracefully
        if ($users->isEmpty()) {
            $this->command->info('No users found. Skipping held purchase seeder.');
            return;
        }

        // Generate dates from the past 5 days
        $datesToSeed = [
            Carbon::now()->subDays(1),
            Carbon::now()->subDays(2),
            Carbon::now()->subDays(3),
            Carbon::now()->subDays(4),
            Carbon::now()->subDays(5)
        ];

        foreach ($users as $user) {
            foreach ($datesToSeed as $date) {
            $purchase = HeldPurchase::create([
                'user_id' => $user->id,
                'supplier_id' => $supplier ? $supplier->id : null,
                'supplier_name' => $supplier ? $supplier->company_name : 'Demo Supplier',
                'warehouse_id' => $warehouse ? $warehouse->id : null,
                'product_store_id' => $store ? $store->id : null,
                'transport_no' => 'TRN-' . rand(1000, 9999),
                'delivery_man' => 'Demo Man ' . rand(1, 5),
                'purchase_date' => $date->format('d-m-Y'),
                'supplier_remarks' => 'Seeder Test ' . rand(100, 999),
                'cart_data' => [
                    [
                        'id' => rand(1, 10),
                        'name' => 'Sample Product ' . rand(1, 10),
                        'qty' => rand(1, 100),
                        'price' => rand(10, 500),
                        'options' => [
                            'barcode' => null,
                            'discount' => 0,
                            'item_discount' => 0,
                            'item_vat' => 0,
                            'weight' => '1kg',
                            'brand_id' => 1,
                            'type' => 'bag',
                            'code' => 'SP-00' . rand(1,9),
                            'sort_index' => microtime(true)
                        ]
                    ]
                ]
            ]);

            // Override timestamps to set them to past dates
            $purchase->timestamps = false;
            $purchase->created_at = $date;
            $purchase->updated_at = $date;
            $purchase->save();
        }
        }

        $this->command->info('Successfully seeded held purchases for all users with randomized created_at dates.');
    }
}
