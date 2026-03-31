<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $view_products = Permission::create(['name' => 'view products']);
        $manage_inventory = Permission::create(['name' => 'manage inventory']);
        $manage_users = Permission::create(['name' => 'manage users']);

        $viewer = Role::create(['name' => 'viewer']);
        $viewer->givePermissionTo($view_products);

        $operator = Role::create(['name' => 'operator']);
        $operator->givePermissionTo([
            $view_products,
            $manage_inventory
        ]);

        $manager = Role::create(['name' => 'warehouse_manager']);
        $manager->givePermissionTo([
            $view_products,
            $manage_inventory,
            $manage_users
        ]);
    }
}
