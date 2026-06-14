<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->integer('quantity')->default(0)->after('low_stock_threshold');
        });

        if (Schema::hasTable('inventory_movements')) {
            $productIds = DB::table('products')->pluck('id');

            foreach ($productIds as $productId) {
                $quantity = DB::table('inventory_movements')
                    ->where('product_id', $productId)
                    ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
                    ->value('total');

                DB::table('products')
                    ->where('id', $productId)
                    ->update(['quantity' => (int) $quantity]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['description', 'quantity']);
        });
    }
};
