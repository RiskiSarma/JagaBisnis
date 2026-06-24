<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'business_id', 'user_id', 'paket', 'price', 'duration_days',
        'payment_method', 'proof_path', 'status', 'admin_note',
        'reviewed_by', 'reviewed_at',
        'midtrans_order_id', 'midtrans_transaction_id', 'snap_token',
        'midtrans_payment_type', 'midtrans_raw_response',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'midtrans_raw_response' => 'array',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function priceFor(string $paket): int
    {
        return match ($paket) {
            'starter'  => 0,
            'pro'      => 299000,
            'business' => 799000,
            default    => 0,
        };
    }
}