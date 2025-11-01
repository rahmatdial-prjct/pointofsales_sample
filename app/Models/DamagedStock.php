<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DamagedStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'product_id',
        'return_item_id',
        'quantity',
        'condition',
        'reason',
        'action_taken',
        'disposed_at',
        'disposed_by',
        'notes',
    ];

    protected $casts = [
        'disposed_at' => 'datetime',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function returnItem(): BelongsTo
    {
        return $this->belongsTo(ReturnItem::class);
    }

    public function disposedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disposed_by');
    }

    // Scopes
    public function scopeDamaged($query)
    {
        return $query->where('condition', 'damaged');
    }

    public function scopeDefective($query)
    {
        return $query->where('condition', 'defective');
    }

    public function scopePending($query)
    {
        return $query->whereNull('action_taken');
    }

    public function scopeDisposed($query)
    {
        return $query->whereNotNull('disposed_at');
    }
}
