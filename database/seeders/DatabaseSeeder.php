<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //    'name' => 'Mahmoud Maher',
        //     'email' => 'manager@email.com',
        //     'password' => Has::make('password'),
        //     'tenant_id' => $tenant1->id
        // ]);
        
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(TenantSeeder::class);

    }
}
