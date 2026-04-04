<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/db-check', function () {
    return response()->json([
        'actual_host' => config('database.connections.mysql.host'),
        'env_host' => env('DB_HOST'),
        'database' => config('database.connections.mysql.database'),
        'app_env' => app()->environment(),
    ]);
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::post('/products/{product}/movements', [ProductController::class, 'storeMovment']);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole']);
});
