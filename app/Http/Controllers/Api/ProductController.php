<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovementRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\InventoryMovementResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\InventoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private InventoryService $inventoryService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->productService->paginate(
            $request->integer('per_page', 15)
        );

        return ProductResource::collection($products)->additional([
            'status' => 'success',
            'message' => 'Products retrieved successfully.',
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create(
            $request->validated(),
            Auth::user()->tenant_id
        );

        return (new ProductResource($product))
            ->additional([
                'status' => 'success',
                'message' => 'Product created successfully.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        return (new ProductResource($product))->additional([
            'status' => 'success',
            'message' => 'Product retrieved successfully.',
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->update($product, $request->validated());

        return (new ProductResource($product))->additional([
            'status' => 'success',
            'message' => 'Product updated successfully.',
        ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        return $this->successResponse(message: 'Product deleted successfully.');
    }

    public function storeMovement(StoreMovementRequest $request, Product $product)
    {
        $movement = $this->inventoryService->recordMovement(
            $product,
            $request->validated(),
            Auth::id()
        );

        return $this->createdResponse([
            'movement' => InventoryMovementResource::make($movement)->resolve(),
        ], 'Inventory movement recorded successfully.');
    }

    public function lowStock()
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->productService->lowStock();

        return ProductResource::collection($products)->additional([
            'status' => 'success',
            'message' => 'Low stock products retrieved successfully.',
        ]);
    }
}
