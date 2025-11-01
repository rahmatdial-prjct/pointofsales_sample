<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ReturnItem;
use App\Models\User;

class ReturnTransaction extends Model
{
    use HasFactory, SoftDeletes; // Removed HasAuditLogs trait

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'branch_id',
        'user_id',
        'transaction_id',
        'reason',
        'total',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function returnItems(): HasMany
    {
        return $this->hasMany(ReturnItem::class, 'return_transaction_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Removed auditLogs relationship as audit log feature is removed
    // public function auditLogs(): HasMany
    // {
    //     return $this->hasMany(AuditLog::class, 'return_id');
    // }
}
