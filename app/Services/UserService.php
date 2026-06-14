<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function assignRole(User $user, string $role): User
    {
        setPermissionsTeamId($user->tenant_id);
        $user->syncRoles([$role]);

        return $user->fresh();
    }
}
