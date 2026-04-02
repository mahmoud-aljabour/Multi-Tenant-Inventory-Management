<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovementRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\InventoryMovementResource;
use App\Http\Resources\ProductResource;
use App\Jobs\LowStockNotificationJob;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('movements');
        return ProductResource::collection($products->paginate());
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            ...$request->validated(),
            'tenant_id' => Auth::user()->tenant_id
        ]);

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function storeMovment(StoreMovementRequest $request, Product $product)
    {
        if ($product->tenant_id !== Auth::user()->tenant_id) {
            return response()->json([
                'message' => 'غير مصرح لك'
            ], 403);
        }

        // out 
        if ($request->type === 'out' && $product->quantity < $request->quantity) {
            return response()->json([
                'message' => 'الكمية غير كافية'
            ], 422);
        }

        // in
        $movement = InventoryMovement::create([
            ...$request->validated(),
            'product_id' => $product->id,
            'created_by' => Auth::id()
        ]);

        if ($request->type === 'out') {
            LowStockNotificationJob::dispatch($product->fresh());
        }

        return response()->json([
            'message' => 'success',
            'movement' =>  new InventoryMovementResource($movement)
        ]);
    }

    public function lowStock()
    {
        $products = Product::lowStock();
        return ProductResource::collection($products);
    }
}
