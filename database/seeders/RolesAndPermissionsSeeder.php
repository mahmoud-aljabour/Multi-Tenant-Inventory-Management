<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        setPermissionsTeamId(null);

        $viewProducts = Permission::firstOrCreate(['name' => 'view products']);
        $createProducts = Permission::firstOrCreate(['name' => 'create products']);
        $manageInventory = Permission::firstOrCreate(['name' => 'manage inventory']);
        $manageUsers = Permission::firstOrCreate(['name' => 'manage users']);

        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->syncPermissions([$viewProducts]);

        $operator = Role::firstOrCreate(['name' => 'operator']);
        $operator->syncPermissions([$viewProducts, $manageInventory]);

        $manager = Role::firstOrCreate(['name' => 'warehouse_manager']);
        $manager->syncPermissions([
            $viewProducts,
            $createProducts,
            $manageInventory,
            $manageUsers,
        ]);
    }
}
