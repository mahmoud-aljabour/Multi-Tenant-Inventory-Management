<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsersWithRoles;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use CreatesUsersWithRoles, RefreshDatabase;

    public function test_user_cannot_view_product_from_another_tenant(): void
    {
        $this->seedRoles();

        $tenantA = $this->createTenant(['name' => 'Tenant A']);
        $tenantB = $this->createTenant(['name' => 'Tenant B']);

        $userA = $this->createUserForTenant($tenantA, 'warehouse_manager');
        $productB = Product::factory()->create(['tenant_id' => $tenantB->id]);

        $this->actingAsApiUser($userA)
            ->getJson("/api/products/{$productB->id}")
            ->assertNotFound();
    }

    public function test_user_only_sees_own_tenant_products_in_list(): void
    {
        $this->seedRoles();

        $tenantA = $this->createTenant(['name' => 'Tenant A']);
        $tenantB = $this->createTenant(['name' => 'Tenant B']);

        $userA = $this->createUserForTenant($tenantA, 'viewer');

        Product::factory()->count(2)->create(['tenant_id' => $tenantA->id]);
        Product::factory()->count(3)->create(['tenant_id' => $tenantB->id]);

        $response = $this->actingAsApiUser($userA)
            ->getJson('/api/products');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_user_cannot_assign_role_to_user_from_another_tenant(): void
    {
        $this->seedRoles();

        $tenantA = $this->createTenant(['name' => 'Tenant A']);
        $tenantB = $this->createTenant(['name' => 'Tenant B']);

        $managerA = $this->createUserForTenant($tenantA, 'warehouse_manager');
        $userB = $this->createUserForTenant($tenantB, 'viewer');

        $this->actingAsApiUser($managerA)
            ->postJson("/api/users/{$userB->id}/assign-role", [
                'role' => 'operator',
            ])
            ->assertNotFound();
    }
}
