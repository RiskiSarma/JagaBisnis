<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\PlanLimitService;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'type', 'status', 'feat_stok',
        'total_transactions', 'total_revenue',
        'subscription_status', 'trial_ends_at', 
        'subscription_ends_at', 'paket',
        'midtrans_merchant_id', 'midtrans_server_key', 'midtrans_client_key',
        'midtrans_is_production', 'midtrans_is_active', 'midtrans_connected_at',
    ];

    protected $casts = [
        'feat_stok' => 'boolean',
        'trial_ends_at'        => 'datetime',
        'subscription_ends_at' => 'datetime',
        'midtrans_server_key'    => 'encrypted',
        'midtrans_client_key'    => 'encrypted',
        'midtrans_is_production' => 'boolean',
        'midtrans_is_active'     => 'boolean',
        'midtrans_connected_at'  => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function promos(): HasMany
    {
        return $this->hasMany(Promo::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function manager()
    {
        return $this->users()->whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
    }

    public function hasAccess(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->subscription_status === 'active'
            && $this->subscription_ends_at
            && $this->subscription_ends_at->isFuture()) {
            return true;
        }

        if ($this->subscription_status === 'trial'
            && $this->trial_ends_at
            && $this->trial_ends_at->isFuture()) {
            return true;
        }

        return false;
    }

    public function refreshSubscriptionStatus(): void
    {
        if ($this->subscription_status === 'trial'
            && $this->trial_ends_at
            && $this->trial_ends_at->isPast()) {
            $this->subscription_status = 'expired';
            $this->save();
            return;
        }

        if ($this->subscription_status === 'active'
            && $this->subscription_ends_at
            && $this->subscription_ends_at->isPast()) {
            $this->subscription_status = 'expired';
            $this->save();
        }
    }

    public function daysRemaining(): int
    {
        $end = $this->subscription_status === 'active'
            ? $this->subscription_ends_at
            : $this->trial_ends_at;

        if (!$end) return 0;

        return max(0, now()->diffInDays($end, false));
    }
    /**
     * Apakah bisnis ini sudah menghubungkan akun Midtrans mereka sendiri
     * untuk menerima pembayaran digital di kasir.
     */
    public function hasMidtransConnected(): bool
    {
        return $this->midtrans_is_active
            && !empty($this->midtrans_server_key)
            && !empty($this->midtrans_client_key);
    }
    public function hasFeature(string $feature): bool
    {
        return PlanLimitService::hasFeature($this, $feature);
    }

    public function canAddKasir(): bool
    {
        return PlanLimitService::canAddKasir($this);
    }

    public function canAddProduk(): bool
    {
        return PlanLimitService::canAddProduk($this);
    }
    // app/Models/Business.php
    public function recalculateRevenue()
    {
        $this->total_revenue = $this->transactions()->sum('total');
        $this->total_transactions = $this->transactions()->count();
        $this->save();

        return $this->total_revenue;
    }

    // Static method untuk semua bisnis
    public static function recalculateAll()
    {
        return self::all()->each->recalculateRevenue();
    }
}
?>