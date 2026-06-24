<?php

namespace App\Services;

use App\Models\Business;

class PlanLimitService
{
    /**
     * Definisi limit & fitur per paket.
     */
    protected static array $plans = [
        'starter' => [
            'max_kasir'       => 1,
            'max_produk'      => 100,
            'max_bisnis'      => 1,
            'fitur' => [
                'pos', 'produk', 'transaksi', 'laporan_dasar',
            ],
        ],
        'pro' => [
            'max_kasir'       => 5,
            'max_produk'      => null, // unlimited
            'max_bisnis'      => 1,
            'fitur' => [
                'pos', 'produk', 'transaksi', 'laporan_dasar',
                'laporan_lengkap', 'customer', 'promo', 'kirim_wa',
            ],
        ],
        'business' => [
            'max_kasir'       => null, // unlimited
            'max_produk'      => null,
            'max_bisnis'      => 5,
            'fitur' => [
                'pos', 'produk', 'transaksi', 'laporan_dasar',
                'laporan_lengkap', 'customer', 'promo', 'kirim_wa',
                'multi_bisnis', 'midtrans_kasir',
            ],
        ],
    ];

    public static function getPlan(string $paket): array
    {
        return self::$plans[$paket] ?? self::$plans['starter'];
    }

    public static function hasFeature(Business $business, string $feature): bool
    {
        $plan = self::getPlan($business->paket);
        return in_array($feature, $plan['fitur']);
    }

    public static function maxKasir(Business $business): ?int
    {
        return self::getPlan($business->paket)['max_kasir'];
    }

    public static function maxProduk(Business $business): ?int
    {
        return self::getPlan($business->paket)['max_produk'];
    }

    public static function canAddKasir(Business $business): bool
    {
        $max = self::maxKasir($business);
        if ($max === null) return true; // unlimited

        $currentCount = $business->users()
            ->whereHas('roles', fn($q) => $q->where('name', 'kasir'))
            ->count();

        return $currentCount < $max;
    }

    public static function canAddProduk(Business $business): bool
    {
        $max = self::maxProduk($business);
        if ($max === null) return true;

        return $business->products()->count() < $max;
    }
}