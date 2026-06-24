<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    public function handle(MidtransService $midtrans): JsonResponse
    {
        try {
            $result = $midtrans->handleNotification();
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'invalid notification'], 400);
        }

        $orderId = $result['order_id'];
        $status  = $result['transaction_status'];
        $fraud   = $result['fraud_status'] ?? null;
        $isSuccess = in_array($status, ['capture', 'settlement'])
                     && in_array($fraud, [null, 'accept']);

        // ── Cek apakah ini transaksi SUBSCRIPTION (prefix SUB-)
        if (str_starts_with($orderId, 'SUB-')) {
            return $this->handleSubscriptionNotification($result, $isSuccess);
        }

        // ── Cek apakah ini transaksi KASIR (prefix TXN-)
        if (str_starts_with($orderId, 'TXN-')) {
            return $this->handleTransactionNotification($result, $isSuccess);
        }

        return response()->json(['message' => 'unknown order type'], 400);
    }

    protected function handleSubscriptionNotification(array $result, bool $isSuccess): JsonResponse
    {
        $subscription = Subscription::where('midtrans_order_id', $result['order_id'])->first();

        if (!$subscription) {
            return response()->json(['message' => 'subscription not found'], 404);
        }

        $subscription->midtrans_transaction_id = $result['transaction_id'];
        $subscription->midtrans_payment_type   = $result['payment_type'];
        $subscription->midtrans_raw_response   = $result['raw'];

        if ($isSuccess && $subscription->status !== 'approved') {
            $subscription->status      = 'approved';
            $subscription->reviewed_at = now();
            $subscription->save();

            $business  = $subscription->business;
            $startFrom = ($business->subscription_status === 'active' && $business->subscription_ends_at?->isFuture())
                ? $business->subscription_ends_at
                : now();

            $business->update([
                'paket'                => $subscription->paket,
                'subscription_status'  => 'active',
                'subscription_ends_at' => $startFrom->copy()->addDays($subscription->duration_days),
            ]);
        } elseif (in_array($result['transaction_status'], ['cancel', 'deny', 'expire'])) {
            $subscription->status     = 'rejected';
            $subscription->admin_note = 'Otomatis ditolak: ' . $result['transaction_status'];
            $subscription->save();
        } else {
            $subscription->save();
        }

        return response()->json(['message' => 'ok']);
    }

    protected function handleTransactionNotification(array $result, bool $isSuccess): JsonResponse
    {
        $transaction = Transaction::where('midtrans_order_id', $result['order_id'])->first();

        if (!$transaction) {
            return response()->json(['message' => 'transaction not found'], 404);
        }

        $transaction->midtrans_transaction_id = $result['transaction_id'];
        $transaction->midtrans_payment_type   = $result['payment_type'];

        if ($isSuccess) {
            // Otomatis tandai transaksi sebagai lunas
            $transaction->status = 'lunas';
            $transaction->save();

            // Update total revenue bisnis
            $business = $transaction->business;
            $business->increment('total_revenue', $transaction->total);
        } elseif (in_array($result['transaction_status'], ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'batal';
            $transaction->save();
        } else {
            $transaction->save();
        }

        return response()->json(['message' => 'ok']);
    }
}