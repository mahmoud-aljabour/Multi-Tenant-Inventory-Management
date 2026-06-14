<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsersWithRoles;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use CreatesUsersWithRoles, RefreshDatabase;

    public function test_warehouse_manager_can_create_product(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $user = $this->createUserForTenant($tenant, 'warehouse_manager');

        $response = $this->actingAsApiUser($user)
            ->postJson('/api/products', [
                'name' => 'test product',
                'price' => 55,
                'low_stock_threshold' => 10,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.quantity', 0);
    }

    public function test_viewer_cannot_create_product(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $user = $this->createUserForTenant($tenant, 'viewer');

        $this->actingAsApiUser($user)
            ->postJson('/api/products', [
                'name' => 'test product',
                'price' => 55,
                'low_stock_threshold' => 10,
            ])
            ->assertForbidden();
    }

    public function test_warehouse_manager_can_update_and_delete_product(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $manager = $this->createUserForTenant($tenant, 'warehouse_manager');

        $product = Product::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Old Name',
        ]);

        $this->actingAsApiUser($manager)
            ->putJson("/api/products/{$product->id}", [
                'name' => 'Updated Name',
                'price' => 99.99,
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Name');

        $this->actingAsApiUser($manager)
            ->deleteJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Product deleted successfully.');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_inventory_movement_updates_product_quantity(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $user = $this->createUserForTenant($tenant, 'operator');

        $product = Product::factory()->create([
            'tenant_id' => $tenant->id,
            'quantity' => 0,
            'low_stock_threshold' => 5,
        ]);

        $this->actingAsApiUser($user)
            ->postJson("/api/products/{$product->id}/movements", [
                'type' => 'in',
                'quantity' => 10,
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 10,
        ]);
    }

    public function test_low_stock_endpoint_returns_products_below_threshold(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $viewer = $this->createUserForTenant($tenant, 'viewer');

        Product::factory()->create([
            'tenant_id' => $tenant->id,
            'quantity' => 2,
            'low_stock_threshold' => 5,
        ]);

        Product::factory()->create([
            'tenant_id' => $tenant->id,
            'quantity' => 20,
            'low_stock_threshold' => 5,
        ]);

        $this->actingAsApiUser($viewer)
            ->getJson('/api/products/low-stock')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
