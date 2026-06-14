<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id', 'name', 'price', 'stock',
        'stock_mode', 'category', 'image', 'color',
    ];
    protected $appends = ['image_url'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_mode === 'tracked' && $this->stock <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock_mode === 'tracked' && $this->stock > 0 && $this->stock <= 5;
    }

    public function decreaseStock(int $qty): void
    {
        if ($this->stock_mode === 'tracked') {
            $this->decrement('stock', $qty);
        }
    }
    public function scopeForBusiness($q, int $bizId) { 
        return $q->where('business_id', $bizId); 
    }
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
?>