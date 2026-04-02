<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant1 = Tenant::create([
            'name' => 'CO 1',
            'address' => 'USA'
        ]);

        $tenant2 = Tenant::create([
            'name' => 'CO 2',
            'address' => 'USA'
        ]);
        $user =User::create([
            'name' => 'Mahmoud Maher',
            'email' => 'manager@email.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant1->id
        ]);

        setPermissionsTeamId($tenant1->id);
        $user->assignRole('warehouse_manager');
    }
}
