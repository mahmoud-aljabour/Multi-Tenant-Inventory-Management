<?php

namespace Tests\Feature;

use App\Jobs\LowStockNotificationJob;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\Concerns\CreatesUsersWithRoles;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use CreatesUsersWithRoles, RefreshDatabase;

    public function test_operator_can_record_stock_in_movement(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $operator = $this->createUserForTenant($tenant, 'operator');

        $product = Product::factory()->create([
            'tenant_id' => $tenant->id,
            'quantity' => 5,
            'low_stock_threshold' => 3,
        ]);

        $this->actingAsApiUser($operator)
            ->postJson("/api/products/{$product->id}/movements", [
                'type' => 'in',
                'quantity' => 10,
                'note' => 'Restock',
            ])
            ->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 15,
        ]);
    }

    public function test_out_movement_fails_when_stock_is_insufficient(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $operator = $this->createUserForTenant($tenant, 'operator');

        $product = Product::factory()->create([
            'tenant_id' => $tenant->id,
            'quantity' => 5,
            'low_stock_threshold' => 2,
        ]);

        $this->actingAsApiUser($operator)
            ->postJson("/api/products/{$product->id}/movements", [
                'type' => 'out',
                'quantity' => 10,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['quantity']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 5,
        ]);
    }

    public function test_low_stock_job_dispatched_when_threshold_reached(): void
    {
        Queue::fake();

        $this->seedRoles();
        $tenant = $this->createTenant();
        $operator = $this->createUserForTenant($tenant, 'operator');

        $product = Product::factory()->create([
            'tenant_id' => $tenant->id,
            'quantity' => 6,
            'low_stock_threshold' => 5,
        ]);

        $this->actingAsApiUser($operator)
            ->postJson("/api/products/{$product->id}/movements", [
                'type' => 'out',
                'quantity' => 2,
            ])
            ->assertStatus(201);

        Queue::assertPushed(LowStockNotificationJob::class);
    }

    public function test_viewer_cannot_record_inventory_movement(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $viewer = $this->createUserForTenant($tenant, 'viewer');

        $product = Product::factory()->create([
            'tenant_id' => $tenant->id,
            'quantity' => 10,
        ]);

        $this->actingAsApiUser($viewer)
            ->postJson("/api/products/{$product->id}/movements", [
                'type' => 'out',
                'quantity' => 1,
            ])
            ->assertForbidden();
    }
}
