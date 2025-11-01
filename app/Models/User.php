<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// Removed HasAuditLogs trait usage
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transaction;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'branch_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'employee_id');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ReturnTransaction::class);
    }

    // Role Methods
    public function isDirector(): bool
    {
        return $this->role === 'director';
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    // Permission Methods
    public function canManageUsers(): bool
    {
        return $this->isDirector() || $this->isManager();
    }

    public function canManageProducts(): bool
    {
        return $this->isDirector() || $this->isManager();
    }

    public function canManageStock(): bool
    {
        return $this->isDirector() || $this->isManager();
    }

    public function canProcessTransactions(): bool
    {
        return $this->isEmployee() || $this->isManager();
    }

    public function canProcessReturns(): bool
    {
        return $this->isEmployee() || $this->isManager();
    }

    public function canViewReports(): bool
    {
        return $this->isDirector() || $this->isManager();
    }

    // Scope Methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}
