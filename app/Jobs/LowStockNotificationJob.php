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

    /**
     * Create a new job instance.
     */
    public function __construct(public Product $product)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->product->quantity <= $this->product->low_stock_threshold) {
            $managers = User::where('tenant_id', $this->product->tenant_id)
                ->get()
                ->filter(function($user){
                    setPermissionsTeamId($this->product->tenant_id);
                    return $user->hasRole('warehouse_manager');
                });

            foreach ($managers as $manager) {
                Log::info(
                    "message",
                    [
                        'product' => $this->product->name,
                        'quantity' => $this->product->quantity,
                        'mananger' => $manager->name
                    ]
                );
            }
        }
    }
}
