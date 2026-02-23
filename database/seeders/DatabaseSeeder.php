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
        // Create Super Admin user first
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'phone' => '01712345678',
                'password' => bcrypt('123456'),
            ]
        );

        // Then seed roles and permissions
        $this->call(RolePermissionSeeder::class);

        // Assign Super Admin role to the first user
        $superAdminUser->assignRole('Super Admin');

        // Run other seeders
        $this->call([
            DashboardSeeder::class,
        ]);
    }
}
