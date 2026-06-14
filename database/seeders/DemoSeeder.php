<?php

namespace Database\Seeders;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Acme Electronics',
                'address' => 'Amman, Jordan',
                'description' => 'Electronics distributor demo tenant',
                'users' => [
                    ['name' => 'Acme Manager', 'email' => 'manager@acme.test', 'role' => 'warehouse_manager'],
                    ['name' => 'Acme Operator', 'email' => 'operator@acme.test', 'role' => 'operator'],
                    ['name' => 'Acme Viewer', 'email' => 'viewer@acme.test', 'role' => 'viewer'],
                ],
                'products' => [
                    ['name' => 'Wireless Mouse', 'description' => 'Ergonomic wireless mouse', 'price' => 29.99, 'quantity' => 50, 'low_stock_threshold' => 10],
                    ['name' => 'USB-C Hub', 'description' => '7-in-1 USB-C adapter', 'price' => 45.00, 'quantity' => 8, 'low_stock_threshold' => 10],
                    ['name' => 'Mechanical Keyboard', 'description' => 'RGB mechanical keyboard', 'price' => 89.99, 'quantity' => 3, 'low_stock_threshold' => 5],
                ],
            ],
            [
                'name' => 'Beta Supplies Co.',
                'address' => 'Dubai, UAE',
                'description' => 'Office supplies demo tenant',
                'users' => [
                    ['name' => 'Beta Manager', 'email' => 'manager@beta.test', 'role' => 'warehouse_manager'],
                    ['name' => 'Beta Operator', 'email' => 'operator@beta.test', 'role' => 'operator'],
                ],
                'products' => [
                    ['name' => 'A4 Paper Ream', 'description' => '500 sheets', 'price' => 6.50, 'quantity' => 120, 'low_stock_threshold' => 20],
                    ['name' => 'Ballpoint Pens Box', 'description' => 'Box of 50 pens', 'price' => 12.00, 'quantity' => 15, 'low_stock_threshold' => 25],
                ],
            ],
        ];

        foreach ($tenants as $tenantData) {
            $tenant = Tenant::firstOrCreate(
                ['name' => $tenantData['name']],
                [
                    'address' => $tenantData['address'],
                    'description' => $tenantData['description'],
                ]
            );

            $manager = null;

            foreach ($tenantData['users'] as $userData) {
                $user = User::withoutGlobalScopes()->firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'password' => Hash::make('password'),
                        'tenant_id' => $tenant->id,
                    ]
                );

                setPermissionsTeamId($tenant->id);
                $user->syncRoles([$userData['role']]);

                if ($userData['role'] === 'warehouse_manager') {
                    $manager = $user;
                }
            }

            foreach ($tenantData['products'] as $productData) {
                $product = Product::withoutGlobalScopes()->firstOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'name' => $productData['name'],
                    ],
                    [
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'quantity' => $productData['quantity'],
                        'low_stock_threshold' => $productData['low_stock_threshold'],
                    ]
                );

                if ($manager && $productData['quantity'] > 0) {
                    InventoryMovement::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'type' => 'in',
                            'note' => 'Initial stock',
                        ],
                        [
                            'quantity' => $productData['quantity'],
                            'created_by' => $manager->id,
                        ]
                    );
                }
            }
        }
    }
}
