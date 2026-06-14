<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\CreatesUsersWithRoles;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use CreatesUsersWithRoles, RefreshDatabase;

    public function test_user_can_register_with_new_tenant(): void
    {
        $this->seedRoles();

        $response = $this->postJson('/api/register', [
            'name' => 'Mahmoud Maher',
            'email' => 'manager@acme.com',
            'password' => 'password123',
            'tenant' => [
                'name' => 'Acme Corp',
                'address' => 'Amman, Jordan',
                'description' => 'Demo tenant',
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.user.email', 'manager@acme.com');

        $this->assertDatabaseHas('tenants', ['name' => 'Acme Corp']);
        $this->assertDatabaseHas('users', ['email' => 'manager@acme.com']);

        $user = User::where('email', 'manager@acme.com')->first();
        setPermissionsTeamId($user->tenant_id);
        $this->assertTrue($user->hasRole('warehouse_manager'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $user = $this->createUserForTenant($tenant, 'warehouse_manager', [
            'email' => 'login@acme.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@acme.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => ['user', 'token'],
            ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $this->createUserForTenant($tenant, 'warehouse_manager', [
            'email' => 'login@acme.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@acme.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Invalid credentials.');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $this->seedRoles();
        $tenant = $this->createTenant();
        $user = $this->createUserForTenant($tenant, 'warehouse_manager');

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Logged out successfully.');
    }
}
