<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductType;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Gram'],
            ['name' => 'Kg'],
            ['name' => 'Ml'],
            ['name' => 'Pc'],
            ['name' => 'Bag'],
            ['name' => 'Dozen'],
        ];

        foreach ($types as $type) {
            \App\Models\ProductType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
