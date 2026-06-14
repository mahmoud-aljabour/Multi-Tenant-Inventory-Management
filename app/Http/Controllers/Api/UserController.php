<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('tenant')->paginate(
            $request->integer('per_page', 15)
        );

        return UserResource::collection($users)->additional([
            'status' => 'success',
            'message' => 'Users retrieved successfully.',
        ]);
    }

    public function assignRole(AssignRoleRequest $request, User $user)
    {
        $this->userService->assignRole($user, $request->role);

        return $this->successResponse(message: 'Role assigned successfully.');
    }
}
