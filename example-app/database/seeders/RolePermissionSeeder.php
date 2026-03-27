<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = [

            // User Management
            'view users',
            'create user',
            'update user',
            'delete user',
            'assign roles',

            // Products
            'view product',
            'create product',
            'update product',
            'delete product',

            // Orders
            'view order',
            'create order',
            'approve order',
            'delete order',

            // Invoices
            'view invoice',
            'create invoice',
            'pay invoice',

            // Stock Movements
            'view stock movement',
            'add stock movement',
            'adjust stock',

            // Reports
            'view reports',
            'export reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $sales = Role::firstOrCreate(['name' => 'Sales']);
        $inventory = Role::firstOrCreate(['name' => 'Inventory Manager']);
        $accountant = Role::firstOrCreate(['name' => 'Accountant']);


        // Super Admin → everything
        $superAdmin->givePermissionTo(Permission::all());

        // Admin → manage system and users
        $admin->givePermissionTo([
            'view users',
            'create user',
            'update user',
            'delete user',
            'assign roles',
            'view reports',
            'export reports',
            'view order',
        ]);

        // Sales → orders + invoices
        $sales->givePermissionTo([
            'view order',
            'create order',
            'approve order',
            'view invoice',
            'create invoice',
        ]);

        // Inventory Manager → stock + products
        $inventory->givePermissionTo([
            'view product',
            'create product',
            'update product',
            'delete product',
            'view stock movement',
            'add stock movement',
            'adjust stock',
        ]);

        // Accountant → invoices + reports
        $accountant->givePermissionTo([
            'view invoice',
            'pay invoice',
            'view reports',
            'export reports',
        ]);
    }
}