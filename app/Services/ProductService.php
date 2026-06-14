<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::query()->paginate($perPage);
    }

    public function create(array $data, int $tenantId): Product
    {
        return Product::create([
            ...$data,
            'tenant_id' => $tenantId,
            'quantity' => 0,
        ]);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function lowStock(): Collection
    {
        return Product::lowStock()->get();
    }
}
