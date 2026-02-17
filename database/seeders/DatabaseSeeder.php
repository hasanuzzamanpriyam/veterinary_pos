<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $this->call([
            DashboardSeeder::class,
        ]);
        User::create([
            'name' => 'user',
            'phone' => '01234567890',
            'password' => bcrypt('123456'),
        ]);
    }
}
