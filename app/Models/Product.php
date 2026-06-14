<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'tenant_id',
        'price',
        'low_stock_threshold',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'quantity' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function ($query) {
            if (Auth::check()) {
                $query->where('tenant_id', Auth::user()->tenant_id);
            }
        });
    }
}
