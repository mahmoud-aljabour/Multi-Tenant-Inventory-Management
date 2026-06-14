<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait SetsPermissionTeam
{
    public function before(User $user, string $ability): ?bool
    {
        setPermissionsTeamId($user->tenant_id);

        return null;
    }
}
