@extends('layouts.app')

@section('title', 'Fitur Bisnis')

@section('content')
    <x-slot name="header">Fitur Bisnis</x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header Banner --}}
        <div class="rounded-xl bg-gradient-to-r from-brand to-blue-400 p-5 flex items-center gap-4 shadow-md">
            <div class="shrink-0 bg-white/20 rounded-xl p-2.5">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/>
                    <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>
            <div>
                <h2 class="text-white font-bold text-base leading-tight">Fitur Bisnis</h2>
                <p class="text-white/70 text-sm mt-0.5">Aktifkan atau nonaktifkan modul untuk setiap bisnis secara terpisah</p>
            </div>
        </div>

        {{-- Info notice --}}
        <div class="flex items-start gap-3 bg-brand/10 border border-brand/30 rounded-lg px-4 py-3 text-sm text-brand">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span><strong>Perubahan berlaku langsung.</strong> Menu, tampilan stok, dan validasi di kasir akan menyesuaikan otomatis setelah toggle diubah.</span>
        </div>

        {{-- Per-bisnis cards --}}
        @foreach($businesses as $biz)
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm">

            {{-- Bisnis header --}}
            <div class="flex items-center gap-3 px-5 py-3.5 bg-slate-50 dark:bg-slate-900/40 border-b border-slate-200 dark:border-slate-700">
                <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $biz->status === 'active' ? 'bg-emerald-400' : 'bg-slate-400' }}"></span>
                <strong class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $biz->name }}</strong>
                <span class="ml-auto text-xs text-slate-400">
                    {{ $biz->type }} &bull;
                    <span class="{{ $biz->status === 'active' ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ $biz->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </span>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-700/60 px-5">

                {{-- ── MODUL: STOK ── --}}
                <div class="flex items-start gap-4 py-4">
                    <div class="shrink-0 w-11 h-11 rounded-xl flex items-center justify-center
                        {{ $biz->feat_stok ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-slate-100 dark:bg-slate-700' }}">
                        <svg class="w-5 h-5 {{ $biz->feat_stok ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/>
                            <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Manajemen Stok Produk</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Aktifkan untuk bisnis yang perlu pantau stok. Nonaktifkan untuk bisnis jasa / makanan dibuat fresh yang tidak perlu stok.
                        </p>
                        <p class="text-xs font-bold mt-1.5 {{ $biz->feat_stok ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $biz->feat_stok ? '✓ Aktif — stok terlihat di produk & kasir' : '✗ Nonaktif — stok tersembunyi sepenuhnya' }}
                        </p>
                    </div>
                    <form action="{{ route('sa.businesses.toggle-stok', $biz) }}" method="POST" class="shrink-0 mt-0.5">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2
                            {{ $biz->feat_stok ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}"
                            title="{{ $biz->feat_stok ? 'Nonaktifkan stok' : 'Aktifkan stok' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                {{ $biz->feat_stok ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </form>
                </div>

                {{-- ── MODUL: LOYALTY (coming soon) ── --}}
                <div class="flex items-start gap-4 py-4 opacity-40 pointer-events-none select-none">
                    <div class="shrink-0 w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="6"/>
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Loyalty Point Customer</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Poin reward otomatis untuk setiap transaksi pelanggan.
                        </p>
                        <p class="text-xs font-bold text-slate-400 mt-1.5">Segera Hadir</p>
                    </div>
                    <div class="shrink-0 mt-0.5">
                        <div class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-300 dark:bg-slate-600 cursor-not-allowed">
                            <span class="inline-block h-4 w-4 translate-x-1 transform rounded-full bg-white shadow"></span>
                        </div>
                    </div>
                </div>

                {{-- ── MODUL: MEJA / ANTRIAN (coming soon) ── --}}
                <div class="flex items-start gap-4 py-4 opacity-40 pointer-events-none select-none">
                    <div class="shrink-0 w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path d="M3 5h18M3 12h18M3 19h18"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Manajemen Meja / Antrian</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Nomor meja atau antrian pada setiap transaksi. Cocok untuk F&B.
                        </p>
                        <p class="text-xs font-bold text-slate-400 mt-1.5">Segera Hadir</p>
                    </div>
                    <div class="shrink-0 mt-0.5">
                        <div class="relative inline-flex h-6 w-11 items-center rounded-full bg-slate-300 dark:bg-slate-600 cursor-not-allowed">
                            <span class="inline-block h-4 w-4 translate-x-1 transform rounded-full bg-white shadow"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endforeach

        @if($businesses->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <p class="text-sm">Belum ada bisnis terdaftar.</p>
        </div>
        @endif

    </div>
@endsection