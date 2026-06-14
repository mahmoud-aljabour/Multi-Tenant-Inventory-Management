<?php

namespace App\Services;

use App\Jobs\LowStockNotificationJob;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function recordMovement(Product $product, array $data, int $userId): InventoryMovement
    {
        $movement = DB::transaction(function () use ($product, $data, $userId) {
            $lockedProduct = Product::withoutGlobalScopes()
                ->where('id', $product->id)
                ->where('tenant_id', $product->tenant_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data['type'] === 'out' && $lockedProduct->quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => ['Insufficient stock available.'],
                ]);
            }

            $movement = InventoryMovement::create([
                'product_id' => $lockedProduct->id,
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'note' => $data['note'] ?? null,
                'created_by' => $userId,
            ]);

            $quantityChange = $data['type'] === 'in'
                ? $data['quantity']
                : -$data['quantity'];

            $lockedProduct->increment('quantity', $quantityChange);

            return $movement;
        });

        $freshProduct = $product->fresh();

        if ($data['type'] === 'out' && $freshProduct->quantity <= $freshProduct->low_stock_threshold) {
            LowStockNotificationJob::dispatch($freshProduct);
        }

        return $movement;
    }
}
