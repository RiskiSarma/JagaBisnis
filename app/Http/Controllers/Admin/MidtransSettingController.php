<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MidtransSettingController extends Controller
{
    public function index(): View
    {
        $business = Auth::user()->business;

        return view('admin.midtrans-setting.index', [
            'business' => $business,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'midtrans_merchant_id'    => ['required', 'string', 'max:100'],
            'midtrans_server_key'     => ['required', 'string', 'max:255'],
            'midtrans_client_key'     => ['required', 'string', 'max:255'],
            'midtrans_is_production'  => ['sometimes', 'boolean'],
        ]);

        $business = Auth::user()->business;

        $business->update([
            'midtrans_merchant_id'   => $request->midtrans_merchant_id,
            'midtrans_server_key'    => $request->midtrans_server_key, // otomatis dienkripsi via cast
            'midtrans_client_key'    => $request->midtrans_client_key,
            'midtrans_is_production' => $request->boolean('midtrans_is_production'),
            'midtrans_is_active'     => true,
            'midtrans_connected_at'  => now(),
        ]);

        return redirect()->route('admin.midtrans-setting.index')
            ->with('success', 'Akun Midtrans berhasil terhubung. Kasir sekarang bisa menerima pembayaran QRIS/E-wallet.');
    }

    public function disconnect(): RedirectResponse
    {
        Auth::user()->business->update([
            'midtrans_is_active' => false,
        ]);

        return redirect()->route('admin.midtrans-setting.index')
            ->with('success', 'Akun Midtrans berhasil diputuskan.');
    }
}