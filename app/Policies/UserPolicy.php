<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\SetsPermissionTeam;

class UserPolicy
{
    use SetsPermissionTeam;

    public function viewAny(User $user): bool
    {
        return $user->can('manage users');
    }

    public function assignRole(User $user, User $model): bool
    {
        return $user->tenant_id === $model->tenant_id
            && $user->can('manage users');
    }
}
