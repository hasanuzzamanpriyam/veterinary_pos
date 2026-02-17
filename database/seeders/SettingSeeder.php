<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::create([
            'app_name' => 'Inventory',
            'website_name' => 'Firoz Enterprise',
            'favicon' => null,
        ]);
    }
}
