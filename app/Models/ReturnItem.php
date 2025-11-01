<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItem extends Model
{
    use HasFactory; // Removed HasAuditLogs trait

    protected $fillable = [
        'return_transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'reason',
        'condition',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function returnTransaction(): BelongsTo
    {
        return $this->belongsTo(ReturnTransaction::class, 'return_transaction_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Removed auditLogs relationship as audit log feature is removed
    // public function auditLogs(): HasMany
    // {
    //     return $this->hasMany(AuditLog::class, 'return_item_id');
    // }

    // Methods
    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->price;
        $this->save();
    }
}
