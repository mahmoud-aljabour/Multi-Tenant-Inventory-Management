<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return $this->createdResponse([
            'user' => UserResource::make($user)->resolve(),
        ], 'User created successfully.');
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        if ($result === null) {
            return $this->errorResponse('Invalid credentials.', 401);
        }

        return $this->successResponse([
            'user' => UserResource::make($result['user'])->resolve(),
            'token' => $result['token'],
        ], 'Logged in successfully.');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->successResponse(message: 'Logged out successfully.');
    }
}
