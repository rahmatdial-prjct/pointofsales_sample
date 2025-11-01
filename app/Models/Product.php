<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'description',
        'image',
        'base_price',
        'stock',
        'is_active',
        'branch_id',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Stock calculation methods
    public function getCalculatedStockAttribute()
    {
        // Get current stock from database
        return $this->stock;
    }

    public function getActualStockMovements()
    {
        // Calculate stock movements for validation
        $soldQuantity = $this->transactionItems()
            ->whereHas('transaction', function($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');

        $returnedGoodQuantity = $this->returnItems()
            ->where('condition', 'good')
            ->whereHas('returnTransaction', function($query) {
                $query->where('status', 'approved');
            })
            ->sum('quantity');

        $damagedQuantity = DamagedStock::where('product_id', $this->id)
            ->where('branch_id', $this->branch_id)
            ->sum('quantity');

        return [
            'sold' => $soldQuantity,
            'returned_good' => $returnedGoodQuantity,
            'damaged' => $damagedQuantity,
            'current_stock' => $this->stock
        ];
    }

    public function validateStockConsistency()
    {
        $calculatedStock = $this->getCalculatedStockAttribute();
        return $this->stock === $calculatedStock;
    }

    public function scopeLowStock($query)
    {
        return $query;
    }
}
