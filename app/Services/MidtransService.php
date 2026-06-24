<?php

namespace App\Services;

use App\Models\Subscription;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;
use Midtrans\Notification;
use App\Models\Business;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    /**
     * Konfigurasi untuk transaksi SUBSCRIPTION — selalu pakai akun Midtrans
     * milik platform (Jagabisnis), karena ini bisnis kamu sendiri.
     */
    public function configurePlatform(): void
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Konfigurasi untuk transaksi KASIR — pakai akun Midtrans milik
     * bisnis tersebut, supaya uang masuk ke rekening mereka sendiri.
     */
    public function configureForBusiness(Business $business): bool
    {
        if (!$business->hasMidtransConnected()) {
            return false;
        }

        Config::$serverKey    = $business->midtrans_server_key;
        Config::$isProduction = $business->midtrans_is_production;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        return true;
    }

    public function createSnapTokenForSubscription(Subscription $subscription): string
    {
        $this->configurePlatform();

        $business = $subscription->business;
        $user     = $subscription->user;

        $params = [
            'transaction_details' => [
                'order_id'     => $subscription->midtrans_order_id,
                'gross_amount' => $subscription->price,
            ],
            'item_details' => [[
                'id'       => 'paket-' . $subscription->paket,
                'price'    => $subscription->price,
                'quantity' => 1,
                'name'     => 'Paket ' . ucfirst($subscription->paket) . ' - ' . $subscription->duration_days . ' hari',
            ]],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $business->phone ?? '',
            ],
            'enabled_payments' => ['gopay', 'shopeepay', 'qris', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'],
            'callbacks' => ['finish' => route('admin.subscription.index')],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Untuk transaksi KASIR (Tahap 2) — order_id harus unik per transaksi,
     * dan pakai key milik business, bukan platform.
     */
    public function createSnapTokenForTransaction(
        Business $business,
        \App\Models\Transaction $transaction,
        array $itemDetails
    ): string {
        if (!$this->configureForBusiness($business)) {
            throw new \Exception('Bisnis ini belum menghubungkan akun Midtrans. Aktifkan di menu Pengaturan Pembayaran.');
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->midtrans_order_id,
                'gross_amount' => (int) $transaction->total,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $transaction->customer_name ?? 'Pelanggan',
            ],
            'enabled_payments' => [
                'gopay', 'shopeepay', 'qris',
                'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va',
            ],
            'callbacks' => [
                'finish' => route('kasir.pos'),
            ],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * 🔥 Baru: Create QRIS Payment langsung via CoreApi
     */
    public function createQrisPayment(Business $business, \App\Models\Transaction $transaction): array
    {
        if (!$this->configureForBusiness($business)) {
            throw new \Exception('Bisnis ini belum menghubungkan akun Midtrans.');
        }

        // Pastikan ada order_id
        if (!$transaction->midtrans_order_id) {
            $orderId = 'TXN-' . $transaction->id . '-' . now()->format('YmdHis') . '-' . \Illuminate\Support\Str::random(4);
            $transaction->update(['midtrans_order_id' => $orderId]);
        }

        // Ambil items
        $items = collect($transaction->items ?? [])->map(fn($item) => [
            'id'       => 'prod-' . ($item['id'] ?? uniqid()),
            'price'    => (int) ($item['price'] ?? 0),
            'quantity' => (int) ($item['qty'] ?? 1),
            'name'     => $item['name'] ?? 'Produk',
        ])->toArray();

        if (empty($items)) {
            $items = [[
                'id'       => 'total',
                'price'    => (int) $transaction->total,
                'quantity' => 1,
                'name'     => 'Pembayaran Transaksi #' . $transaction->id,
            ]];
        }

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $transaction->midtrans_order_id,
                'gross_amount' => (int) $transaction->total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $transaction->customer_name ?? 'Pelanggan',
                'phone' => $transaction->customer_phone ?? '',
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
            'callbacks' => [
                'finish' => route('kasir.pos'),
            ],
        ];

        Log::info('QRIS Payment Params:', $params);

        // 🔥 Charge via CoreApi (bukan Snap)
        $response = CoreApi::charge($params);
        
        Log::info('QRIS Payment Response:', $response);

        return [
            'qr_code_url' => $response['qr_code_url'] ?? null,
            'qr_data' => $response,
            'transaction_id' => $response['transaction_id'] ?? null,
        ];
    }

    public function handleNotification(): array
    {
        $notif = new Notification();

        return [
            'order_id'           => $notif->order_id,
            'transaction_status' => $notif->transaction_status,
            'fraud_status'       => $notif->fraud_status,
            'payment_type'       => $notif->payment_type,
            'transaction_id'     => $notif->transaction_id,
            'raw'                => (array) $notif,
        ];
    }
}