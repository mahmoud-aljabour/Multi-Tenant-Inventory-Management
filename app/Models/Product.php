<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tenant_id',
        'price',
        'low_stock_threshold'
    ];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
    public function getQuantityAttribute()
    {
        $in = $this->movements()->where('type', 'in')->sum('quantity');
        $out = $this->movements()->where('type', 'out')->sum('quantity');

        return $in - $out;
    }

    public function scopeLowStock($query)
    {
        return $query->get()->filter(function ($product) {
            return $product->quantity <= $product->low_stock_threshold;
        });
    }

protected static function booted()
    {
        static::addGlobalScope('tenant', function ($query) {
            if (Auth::check()) {
                $query->where('tenant_id', Auth::user()->tenant_id);
            }
        });
    }}
