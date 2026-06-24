<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'paket',
        'durasi',
        'amount',
        'payment_proof',
        'payment_method',
        'transfer_ref',
        'status',
        'notes',
        'reviewed_by',
        'reviewed_at',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'reviewed_at'  => 'datetime',
        'period_start' => 'datetime',
        'period_end'   => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Helpers ────────────────────────────────────────────

    public function isPending(): bool   { return $this->status === 'pending';  }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }

    public function formattedAmount(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public static function priceFor(string $paket, string $durasi = 'monthly'): int
    {
        $prices = [
            'starter'  => ['monthly' => 0,       'yearly' => 0],
            'pro'      => ['monthly' => 299000,   'yearly' => 2990000],
            'business' => ['monthly' => 799000,   'yearly' => 7990000],
        ];

        return $prices[$paket][$durasi] ?? 0;
    }
}