<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Define all permissions organized by module
        $permissions = [
            // Customer Management
            'customer-view',
            'customer-create',
            'customer-edit',
            'customer-delete',
            'customer-export',
            'customer-ledger',
            'customer-statement',

            // Supplier Management
            'supplier-view',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'supplier-export',
            'supplier-ledger',
            'supplier-statement',

            // Product Management
            'product-view',
            'product-create',
            'product-edit',
            'product-delete',
            'product-stock',
            'product-stock-adjustment',
            'product-gallery',

            // Sales Management
            'sales-view',
            'sales-create',
            'sales-edit',
            'sales-delete',
            'sales-return',
            'sales-report',
            'sales-invoice',

            // Purchase Management
            'purchase-view',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',
            'purchase-return',
            'purchase-report',

            // Collection & Payment
            'collection-view',
            'collection-create',
            'collection-delete',
            'collection-report',
            'payment-view',
            'payment-create',
            'payment-delete',
            'payment-report',

            // Employee Management
            'employee-view',
            'employee-create',
            'employee-edit',
            'employee-delete',
            'employee-ledger',
            'employee-payment',
            'designation-manage',

            // Expense Management
            'expense-view',
            'expense-create',
            'expense-edit',
            'expense-delete',
            'expense-category-manage',

            // Reports
            'reports-daily',
            'reports-monthly',
            'reports-yearly',
            'reports-profit-loss',

            // Bank & Transaction Management
            'bank-view',
            'bank-create',
            'bank-edit',
            'bank-delete',
            'transaction-view',
            'transaction-create',
            'transaction-edit',
            'transaction-delete',

            // Cash Maintenance
            'cash-maintenance-view',
            'cash-maintenance-create',
            'cash-maintenance-edit',
            'cash-maintenance-delete',

            // Bonus Management
            'bonus-view',
            'bonus-create',
            'bonus-edit',
            'bonus-delete',

            // Follow Up Management
            'follow-up-customer',
            'follow-up-supplier',

            // Settings
            'category-manage',
            'subcategory-manage',
            'brand-manage',
            'unit-manage',
            'size-manage',
            'customer-type-manage',
            'store-manage',
            'warehouse-manage',
            'price-group-manage',
            'product-group-manage',
            'payment-gateway-manage',

            // User & Role Management
            'role-view',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-view',
            'permission-create',
            'permission-delete',
            'user-role-assign',

            // Database & System
            'database-export',
            'system-settings',

            // Message Management
            'message-view',
            'message-delete',
            'message-reply',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // 1. Super Admin - Full access
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Admin - All business operations except role management
        $admin = Role::create(['name' => 'Admin']);
        $adminPermissions = Permission::whereNotIn('name', [
            'role-view', 'role-create', 'role-edit', 'role-delete',
            'permission-view', 'permission-create', 'permission-delete',
            'user-role-assign'
        ])->get();
        $admin->givePermissionTo($adminPermissions);

        // 3. Manager - View all, manage customers/suppliers, limited editing
        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            // Customer
            'customer-view', 'customer-create', 'customer-edit', 'customer-ledger', 'customer-statement', 'customer-export',
            // Supplier
            'supplier-view', 'supplier-create', 'supplier-edit', 'supplier-ledger', 'supplier-statement', 'supplier-export',
            // Product (view only)
            'product-view', 'product-stock', 'product-gallery',
            // Sales & Purchase
            'sales-view', 'sales-create', 'sales-report', 'sales-invoice',
            'purchase-view', 'purchase-create', 'purchase-report',
            // Collection & Payment
            'collection-view', 'collection-create', 'collection-report',
            'payment-view', 'payment-create', 'payment-report',
            // Employee (view only)
            'employee-view', 'employee-ledger',
            // Reports (all)
            'reports-daily', 'reports-monthly', 'reports-yearly', 'reports-profit-loss',
            // Bank (view)
            'bank-view', 'transaction-view',
            // Follow up
            'follow-up-customer', 'follow-up-supplier',
            // Bonus (view)
            'bonus-view',
            // Messages
            'message-view', 'message-reply',
        ]);

        // 4. Staff - Basic data entry for sales, purchases, customers
        $staff = Role::create(['name' => 'Staff']);
        $staff->givePermissionTo([
            // Customer
            'customer-view', 'customer-create', 'customer-ledger',
            // Supplier
            'supplier-view', 'supplier-create', 'supplier-ledger',
            // Product
            'product-view', 'product-stock',
            // Sales
            'sales-view', 'sales-create', 'sales-invoice',
            // Purchase
            'purchase-view', 'purchase-create',
            // Collection & Payment
            'collection-view', 'collection-create',
            'payment-view', 'payment-create',
            // Follow up
            'follow-up-customer', 'follow-up-supplier',
            // Messages
            'message-view',
        ]);

        // 5. Viewer - Read-only access to reports and listings
        $viewer = Role::create(['name' => 'Viewer']);
        $viewer->givePermissionTo([
            // View only permissions
            'customer-view', 'customer-ledger', 'customer-statement',
            'supplier-view', 'supplier-ledger', 'supplier-statement',
            'product-view', 'product-stock', 'product-gallery',
            'sales-view', 'sales-report',
            'purchase-view', 'purchase-report',
            'collection-view', 'collection-report',
            'payment-view', 'payment-report',
            'employee-view', 'employee-ledger',
            'expense-view',
            'reports-daily', 'reports-monthly', 'reports-yearly', 'reports-profit-loss',
            'bank-view', 'transaction-view',
            'bonus-view',
            'message-view',
        ]);

        // Assign Super Admin role to user with ID 1
        $superUser = User::find(1);
        if ($superUser) {
            $superUser->assignRole('Super Admin');
            $this->command->info('Super Admin role assigned to user ID 1');
        }

        $this->command->info('Roles and permissions have been seeded successfully!');
        $this->command->info('Created roles: Super Admin, Admin, Manager, Staff, Viewer');
        $this->command->info('Created ' . count($permissions) . ' permissions');
    }
}
