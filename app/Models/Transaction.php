<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'business_id', 'user_id', 'customer_id',
        'items', 'subtotal', 'discount', 'total',
        'pay_method', 'cash_received', 'cash_change',
        'status', 'catatan', 'payment_gateway',
        'midtrans_order_id', 'midtrans_transaction_id',
        'snap_token', 'midtrans_payment_type',
        'customer_name','payment_method',
    ];

    protected $casts = [
        'items' => 'array', // JSON auto-cast
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeForBusiness($query, int $bizId)
    {
        return $query->where('business_id', $bizId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    public function scopeLunas($q) { 
        return $q->where('status', 'lunas'); 
    }

}
?>