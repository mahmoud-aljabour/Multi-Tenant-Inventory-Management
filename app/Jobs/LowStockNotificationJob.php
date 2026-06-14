<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class LowStockNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Product $product) {}

    public function handle(): void
    {
        if ($this->product->quantity > $this->product->low_stock_threshold) {
            return;
        }

        $managers = User::where('tenant_id', $this->product->tenant_id)
            ->get()
            ->filter(function ($user) {
                setPermissionsTeamId($this->product->tenant_id);

                return $user->hasRole('warehouse_manager');
            });

        foreach ($managers as $manager) {
            Log::info('Low stock alert', [
                'product' => $this->product->name,
                'quantity' => $this->product->quantity,
                'threshold' => $this->product->low_stock_threshold,
                'manager' => $manager->name,
            ]);
        }
    }
}
