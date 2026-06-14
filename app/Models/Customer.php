<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Customer extends Model
{
    protected $fillable = ['business_id', 'name', 'phone', 'total_visits', 'total_spend', 'last_visit'];
 
    protected $casts = ['last_visit' => 'date'];
 
    public function business() { return $this->belongsTo(Business::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
 
    public function getTierAttribute(): string
    {
        if ($this->total_spend >= 500000) return 'VIP';
        if ($this->total_spend >= 200000) return 'Reguler';
        return 'Baru';
    }
 
    public function getWaNumberAttribute(): string
    {
        $n = preg_replace('/[\s\-().+]/', '', $this->phone ?? '');
        if (str_starts_with($n, '0')) $n = '62' . substr($n, 1);
        return $n;
    }
}