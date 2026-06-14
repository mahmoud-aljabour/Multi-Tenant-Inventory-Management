<?php

namespace Tests\Concerns;

use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Laravel\Sanctum\Sanctum;

trait CreatesUsersWithRoles
{
    protected function seedRoles(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    protected function createTenant(array $attributes = []): Tenant
    {
        return Tenant::factory()->create($attributes);
    }

    protected function createUserForTenant(Tenant $tenant, string $role, array $attributes = []): User
    {
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            ...$attributes,
        ]);

        setPermissionsTeamId($tenant->id);
        $user->assignRole($role);

        return $user;
    }

    protected function actingAsApiUser(User $user): self
    {
        Sanctum::actingAs($user);

        return $this;
    }
}
