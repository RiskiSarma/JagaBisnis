@extends('layouts.app')

@section('title', 'Hubungkan Midtrans')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-7">
        <div class="flex items-start gap-4 mb-5">
            <div class="w-12 h-12 rounded-xl {{ $business->hasMidtransConnected() ? 'bg-emerald-100 dark:bg-emerald-500/15' : 'bg-slate-100 dark:bg-slate-700' }} flex items-center justify-center shrink-0">
                <i class="bi bi-credit-card-2-front {{ $business->hasMidtransConnected() ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-300' }} text-xl"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 dark:text-white text-base mb-1">Pembayaran Digital di Kasir</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Hubungkan akun Midtrans bisnis Anda agar pelanggan bisa bayar via QRIS, GoPay, ShopeePay, dan Virtual Account langsung di kasir. Dana masuk langsung ke rekening Anda sendiri.
                </p>
            </div>
        </div>

        {{-- Status koneksi --}}
        @if($business->hasMidtransConnected())
        <div class="bg-emerald-50 dark:bg-emerald-900/15 border border-emerald-200 dark:border-emerald-800/60 rounded-xl px-4 py-3.5 flex items-center justify-between gap-3 mb-5">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400"></i>
                <p class="text-sm text-emerald-700 dark:text-emerald-300 font-semibold">
                    Terhubung sejak {{ $business->midtrans_connected_at->translatedFormat('d M Y') }}
                </p>
            </div>
            <form action="{{ route('admin.midtrans-setting.disconnect') }}" method="POST" onsubmit="return confirm('Putuskan koneksi Midtrans? Kasir tidak akan bisa menerima pembayaran digital lagi.')">
                @csrf @method('DELETE')
                <button class="text-xs font-bold text-red-600 dark:text-red-400 hover:underline">Putuskan</button>
            </form>
        </div>
        @endif

        {{-- Error --}}
        @if($errors->any())
        <div class="flex items-center gap-2 p-3.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium mb-5">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- PANDUAN DAFTAR MIDTRANS --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-800/50 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="bi bi-info-circle text-blue-600 dark:text-blue-400 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-blue-700 dark:text-blue-400 flex items-center gap-2">
                        Belum punya akun Midtrans?
                    </h4>
                    <div class="text-xs text-blue-600 dark:text-blue-300 space-y-1.5 mt-1.5">
                        <p>Ikuti langkah berikut untuk mendapatkan Server Key &amp; Client Key:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>
                                Daftar akun Midtrans 
                                <span class="font-semibold">(GRATIS)</span>:
                                <br>
                                <a href="https://dashboard.sandbox.midtrans.com/register" target="_blank" 
                                   class="inline-flex items-center gap-1 text-brand font-semibold hover:underline">
                                    <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                    Sandbox (Testing)
                                </a>
                                atau
                                <a href="https://dashboard.midtrans.com/register" target="_blank" 
                                   class="inline-flex items-center gap-1 text-brand font-semibold hover:underline">
                                    <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                    Production (Live)
                                </a>
                            </li>
                            <li>
                                Setelah login, buka menu 
                                <span class="font-semibold bg-blue-100 dark:bg-blue-800/50 px-1.5 py-0.5 rounded">Settings</span> 
                                → 
                                <span class="font-semibold bg-blue-100 dark:bg-blue-800/50 px-1.5 py-0.5 rounded">Access Keys</span>
                            </li>
                            <li>
                                Salin <span class="font-semibold bg-blue-100 dark:bg-blue-800/50 px-1.5 py-0.5 rounded">Server Key</span> 
                                dan <span class="font-semibold bg-blue-100 dark:bg-blue-800/50 px-1.5 py-0.5 rounded">Client Key</span>
                            </li>
                            <li>
                                Tempelkan key di form di bawah lalu klik <span class="font-semibold">Hubungkan Akun Midtrans</span>
                            </li>
                        </ol>
                        <div class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                            <p class="text-yellow-700 dark:text-yellow-400 text-[10px] flex items-start gap-1.5">
                                <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
                                <span>
                                    <strong>Sandbox</strong> untuk testing (pakai kartu dummy, gratis). 
                                    <strong>Production</strong> untuk transaksi real (uang masuk ke rekening Anda).
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.midtrans-setting.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-2">
                    Merchant ID <span class="text-red-500">*</span>
                </label>
                <input type="text" name="midtrans_merchant_id" value="{{ old('midtrans_merchant_id', $business->midtrans_merchant_id) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-brand/30 focus:border-brand outline-none"
                       placeholder="G123456789 atau SB-Merchant-xxx">
                <p class="text-xs text-slate-400 mt-1.5">
                    Format: <span class="font-mono">G123456789</span> (Production) atau <span class="font-mono">SB-Merchant-xxx</span> (Sandbox)
                </p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-2">
                    Server Key <span class="text-red-500">*</span>
                </label>
                <input type="password" name="midtrans_server_key"
                    value="{{ old('midtrans_server_key', $business->midtrans_server_key) }}"  {{-- Tambahkan old value --}}
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-brand/30 focus:border-brand outline-none"
                    placeholder="{{ $business->midtrans_server_key ? '••••••••••••••••' : 'Mid-server-xxxxxxxx' }}">
                <p class="text-xs text-slate-400 mt-1.5">
                    Format: <span class="font-mono">Mid-server-xxx</span> (Production) atau <span class="font-mono">SB-Mid-server-xxx</span> (Sandbox)
                </p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-2">
                    Client Key <span class="text-red-500">*</span>
                </label>
                <input type="text" name="midtrans_client_key" value="{{ old('midtrans_client_key', $business->midtrans_client_key) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-brand/30 focus:border-brand outline-none"
                       placeholder="Mid-client-xxxxxxxx">
                <p class="text-xs text-slate-400 mt-1.5">
                    Format: <span class="font-mono">Mid-client-xxx</span> (Production) atau <span class="font-mono">SB-Mid-client-xxx</span> (Sandbox)
                </p>
            </div>

            <input type="hidden" name="midtrans_is_production" value="0">
            <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl px-4 py-3">
                <input type="checkbox" name="midtrans_is_production" value="1" id="isProd"
                    {{ old('midtrans_is_production', $business->midtrans_is_production) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-brand focus:ring-brand/30">
                <label for="isProd" class="text-sm text-slate-600 dark:text-slate-300">
                    Mode Production (Live)
                </label>
            </div>


            <div class="bg-amber-50 dark:bg-amber-900/15 border border-amber-200 dark:border-amber-800/60 rounded-xl px-4 py-3">
                <p class="text-xs text-amber-700 dark:text-amber-400 flex items-start gap-1.5">
                    <i class="bi bi-shield-check text-sm mt-0.5"></i>
                    <span>
                        <strong>Keamanan:</strong> Server Key disimpan terenkripsi. 
                        Pastikan menggunakan key dari akun Midtrans milik bisnis Anda sendiri, 
                        bukan dari bisnis lain.
                    </span>
                </p>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-sm transition-all shadow-lg shadow-brand/25 flex items-center justify-center gap-2">
                <i class="bi bi-link-45deg"></i> Hubungkan Akun Midtrans
            </button>
        </form>
    </div>

    {{-- Fitur yang tersedia --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-7">
        <h3 class="font-bold text-slate-800 dark:text-white text-sm mb-4 flex items-center gap-2">
            <i class="bi bi-check-circle text-brand"></i>
            Fitur yang akan aktif
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <div class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-700/40 rounded-lg">
                <i class="bi bi-qr-code text-brand text-lg"></i>
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">QRIS</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-700/40 rounded-lg">
                <i class="bi bi-wallet2 text-brand text-lg"></i>
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">GoPay</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-700/40 rounded-lg">
                <i class="bi bi-shop text-brand text-lg"></i>
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">ShopeePay</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-700/40 rounded-lg">
                <i class="bi bi-credit-card text-brand text-lg"></i>
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Virtual Account</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-700/40 rounded-lg">
                <i class="bi bi-building text-brand text-lg"></i>
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Bank Transfer</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-700/40 rounded-lg">
                <i class="bi bi-phone text-brand text-lg"></i>
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">E-Wallet Lainnya</span>
            </div>
        </div>
    </div>
</div>
@endsection