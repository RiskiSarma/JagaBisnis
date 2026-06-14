<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'business_id', 'name', 'description',
        'type', 'value', 'min_buy', 'code', 'status',
    ];

    public function business() { return $this->belongsTo(Business::class); }

    public function scopeActive($q) { return $q->where('status', 'active'); }

    public function calcDiscount(int $subtotal): int
    {
        if ($this->min_buy > 0 && $subtotal < $this->min_buy) return 0;
        return $this->type === 'percent'
            ? (int) round($subtotal * $this->value / 100)
            : $this->value;
    }
}