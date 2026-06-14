<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Policies\Concerns\SetsPermissionTeam;

class ProductPolicy
{
    use SetsPermissionTeam;

    public function viewAny(User $user): bool
    {
        return $user->can('view products');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->tenant_id === $product->tenant_id
            && $user->can('view products');
    }

    public function create(User $user): bool
    {
        return $user->can('create products');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->tenant_id === $product->tenant_id
            && $user->can('create products');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->tenant_id === $product->tenant_id
            && $user->can('create products');
    }

    public function manageInventory(User $user, Product $product): bool
    {
        return $user->tenant_id === $product->tenant_id
            && $user->can('manage inventory');
    }
}
