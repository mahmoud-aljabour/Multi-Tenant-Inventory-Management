<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsersWithRoles;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use CreatesUsersWithRoles, RefreshDatabase;

    public function test_warehouse_manager_can_list_users(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $manager = $this->createUserForTenant($tenant, 'warehouse_manager');
        $this->createUserForTenant($tenant, 'operator', ['email' => 'operator@acme.com']);

        $this->actingAsApiUser($manager)
            ->getJson('/api/users')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(2, 'data');
    }

    public function test_warehouse_manager_can_assign_role_to_tenant_user(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $manager = $this->createUserForTenant($tenant, 'warehouse_manager');
        $viewer = $this->createUserForTenant($tenant, 'viewer', ['email' => 'viewer@acme.com']);

        $this->actingAsApiUser($manager)
            ->postJson("/api/users/{$viewer->id}/assign-role", [
                'role' => 'operator',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Role assigned successfully.');

        setPermissionsTeamId($tenant->id);
        $this->assertTrue($viewer->fresh()->hasRole('operator'));
    }

    public function test_operator_cannot_list_users(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $operator = $this->createUserForTenant($tenant, 'operator');

        $this->actingAsApiUser($operator)
            ->getJson('/api/users')
            ->assertForbidden();
    }
}
