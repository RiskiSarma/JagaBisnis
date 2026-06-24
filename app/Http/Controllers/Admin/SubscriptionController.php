<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $business = Auth::user()->business;
        $business->refreshSubscriptionStatus();

        $pendingSubscription = $business->subscriptions()
            ->where('status', 'pending')
            ->latest()
            ->first();

        $history = $business->subscriptions()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.subscription.index', [
            'business'             => $business,
            'pendingSubscription'  => $pendingSubscription,
            'history'              => $history,
            'midtransClientKey'    => config('midtrans.client_key'),
            'midtransIsProduction' => config('midtrans.is_production'),
        ]);
    }

    /**
     * Untuk paket starter (gratis) — langsung aktif tanpa pembayaran.
     */
    public function storeFree(Request $request): RedirectResponse
    {
        $business = Auth::user()->business;

        $business->update([
            'paket'                => 'starter',
            'subscription_status'  => 'active',
            'subscription_ends_at' => now()->addDays(30),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Paket Starter berhasil diaktifkan.');
    }

    /**
     * Membuat Snap Token untuk pembayaran via Midtrans (QRIS/VA/E-wallet).
     */
    public function createSnapToken(Request $request, MidtransService $midtrans)
    {
        $request->validate([
            'paket' => ['required', 'in:pro,business'],
        ]);

        $business = Auth::user()->business;

        try {
            $subscription = Subscription::create([
                'business_id'       => $business->id,
                'user_id'           => Auth::id(),
                'paket'             => $request->paket,
                'price'             => Subscription::priceFor($request->paket),
                'duration_days'     => 30,
                'payment_gateway'   => 'midtrans',
                'status'            => 'pending',
                'midtrans_order_id' => 'SUB-' . $business->id . '-' . now()->format('YmdHis') . '-' . Str::random(6),
            ]);

            $snapToken = $midtrans->createSnapTokenForSubscription($subscription);

            $subscription->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'order_id'   => $subscription->midtrans_order_id,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Snap Token error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke payment gateway: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Submit pembayaran manual (transfer + upload bukti) — fallback.
     */
    public function storeManual(Request $request): RedirectResponse
    {
        $request->validate([
            'paket'          => ['required', 'in:pro,business'],
            'payment_method' => ['required', 'string', 'max:100'],
            'proof'          => ['required', 'image', 'max:2048'],
        ], [
            'proof.required'          => 'Bukti transfer wajib diupload.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $business  = Auth::user()->business;
        $proofPath = $request->file('proof')->store('payment-proofs', 'public');

        Subscription::create([
            'business_id'     => $business->id,
            'user_id'         => Auth::id(),
            'paket'           => $request->paket,
            'price'           => Subscription::priceFor($request->paket),
            'duration_days'   => 30,
            'payment_method'  => $request->payment_method,
            'payment_gateway' => 'manual',
            'proof_path'      => $proofPath,
            'status'          => 'pending',
        ]);

        return redirect()->route('admin.subscription.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Mohon tunggu konfirmasi dari Admin (1-24 jam).');
    }
}