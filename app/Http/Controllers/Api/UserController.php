<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('tenant')->paginate();
        return UserResource::collection($users);
    }

    public function assignRole(AssignRoleRequest $request, User $user)
    {
        setPermissionsTeamId($user->tenant_id);
        $user->assignRole($request->role);
        return response()->json([
            'message' => 'تمت الاضافة'
        ]);
    }
}
