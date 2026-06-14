<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'type', 'status', 'feat_stok',
        'total_transactions', 'total_revenue',
    ];

    protected $casts = [
        'feat_stok' => 'boolean',
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

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function manager()
    {
        return $this->users()->whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
    }
}
?>