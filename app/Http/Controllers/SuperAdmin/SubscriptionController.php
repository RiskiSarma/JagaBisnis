<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $subscriptions = Subscription::with(['business', 'user'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('superadmin.subscriptions.index', [
            'subscriptions' => $subscriptions,
            'currentStatus' => $status,
        ]);
    }

    public function approve(Subscription $subscription): RedirectResponse
    {
        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $subscription->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $business = $subscription->business;

        // Jika sebelumnya masih aktif, tambahkan durasi dari sisa waktu sekarang
        $startFrom = ($business->subscription_status === 'active' && $business->subscription_ends_at?->isFuture())
            ? $business->subscription_ends_at
            : now();

        $business->update([
            'paket'                => $subscription->paket,
            'subscription_status'  => 'active',
            'subscription_ends_at' => $startFrom->copy()->addDays($subscription->duration_days),
        ]);

        return back()->with('success', 'Pembayaran disetujui. Paket bisnis berhasil diaktifkan/diperpanjang.');
    }

    public function reject(Request $request, Subscription $subscription): RedirectResponse
    {
        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $subscription->update([
            'status'      => 'rejected',
            'admin_note'  => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }
}