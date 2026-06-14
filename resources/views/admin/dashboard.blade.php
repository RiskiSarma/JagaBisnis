@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Page Header --}}
<div class="flex items-start justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Dashboard</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            {{ $biz->name }} &bull; {{ now()->format('d M Y') }}
        </p>
    </div>
    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Bisnis Aktif
    </span>
</div>

{{-- Low Stock Alert --}}
@if($lowStock->count())
<div class="flex items-start gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl mb-5 flex-wrap">
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 shrink-0"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <div class="flex-1 min-w-0">
        <span class="text-sm font-semibold text-amber-800 dark:text-amber-300">⚠️ Stok menipis:
            @foreach($lowStock as $p)
                <strong>{{ $p->name }}</strong> ({{ $p->stock }}){{ !$loop->last ? ', ' : '' }}
            @endforeach
        </span>
    </div>
    <a href="{{ route('admin.products.index') }}"
       class="shrink-0 px-3 py-1 bg-amber-400 text-white text-xs font-bold rounded-lg hover:bg-amber-500 transition-colors">
        Kelola Stok
    </a>
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">

    {{-- Transaksi Hari Ini --}}
    <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 overflow-hidden hover:-translate-y-0.5 transition-transform duration-200 shadow-sm hover:shadow-md">
        <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center opacity-20" style="background:#1A56DB">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/></svg>
        </div>
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full opacity-[0.07] -translate-y-4 translate-x-4" style="background:#1A56DB"></div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 mb-2">Transaksi Hari Ini</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono">{{ $todayTx->count() }}</p>
        @php
            $todayLunas = $todayTx->where('status','lunas')->count();
            $todayBelum = $todayTx->where('status','!=','lunas')->count();
        @endphp
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">
            {{ $todayLunas }} lunas
            @if($todayBelum) &bull; <span class="text-red-500">{{ $todayBelum }} belum</span> @endif
        </p>
    </div>

    {{-- Revenue Hari Ini --}}
    <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 overflow-hidden hover:-translate-y-0.5 transition-transform duration-200 shadow-sm hover:shadow-md">
        <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center opacity-20" style="background:#10B981">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full opacity-[0.07] -translate-y-4 translate-x-4" style="background:#10B981"></div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 mb-2">Revenue Hari Ini</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono">
            @php
                $todayRevK = $todayRev >= 1000000 ? number_format($todayRev/1000000,1).'jt' : ($todayRev >= 1000 ? number_format($todayRev/1000,0).'rb' : $todayRev);
            @endphp
            {{ $todayRevK }}
        </p>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Rp {{ number_format($todayRev,0,',','.') }}</p>
    </div>

    {{-- Total Produk --}}
    <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 overflow-hidden hover:-translate-y-0.5 transition-transform duration-200 shadow-sm hover:shadow-md">
        <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center opacity-20" style="background:#F59E0B">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        </div>
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full opacity-[0.07] -translate-y-4 translate-x-4" style="background:#F59E0B"></div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 mb-2">Total Produk</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono">{{ $totalProds }}</p>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">
            @if($lowStock->count())
                <span class="text-red-500">{{ $lowStock->count() }} stok menipis</span>
            @else
                Semua produk cukup
            @endif
        </p>
    </div>

    {{-- Customer --}}
    <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 overflow-hidden hover:-translate-y-0.5 transition-transform duration-200 shadow-sm hover:shadow-md">
        <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center opacity-20" style="background:#8B5CF6">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full opacity-[0.07] -translate-y-4 translate-x-4" style="background:#8B5CF6"></div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 mb-2">Customer</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono">{{ $biz->customers()->count() }}</p>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Terdaftar</p>
    </div>
</div>

{{-- Shortcut Cards --}}
<div class="grid grid-cols-3 gap-3 mb-5">
    <a href="{{ route('admin.reports.sales') }}" 
       class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="w-12 h-12 mx-auto bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125L10.125 20.25L21 9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3v6m9-6v6" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9h18" />
            </svg>
        </div>
        <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">Lap. Penjualan</div>
    </a>
    <a href="{{ route('admin.products.index') }}" 
       class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="w-12 h-12 mx-auto bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">Kelola Produk</div>
    </a>
    <a href="{{ route('admin.customers.index') }}" 
       class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="w-12 h-12 mx-auto bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 01-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">Customer</div>
    </a>
</div>

{{-- Recent Transactions Table --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex-wrap gap-3">
        <div>
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Transaksi Terbaru</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $recentTx->count() }} transaksi terakhir</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}"
           class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-brand hover:text-brand transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            Lihat Semua
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" style="min-width:520px">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-[0.05em] text-slate-500 dark:text-slate-400">
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Kasir</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($recentTx as $tx)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">
                        {{ $tx->created_at->format('H:i, d M') }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">
                        {{ $tx->customer?->name ?? 'Pelanggan' }}
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                        @php
                            $raw = $tx->getRawOriginal('items');
                            $cnt = is_string($raw) ? count(json_decode($raw,true) ?? []) : (is_array($tx->items) ? count($tx->items) : 0);
                        @endphp
                        {{ $cnt }} item
                    </td>
                    <td class="px-4 py-3 font-bold text-slate-900 dark:text-white font-mono">
                        Rp {{ number_format($tx->total,0,',','.') }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500">
                        {{ $tx->kasir?->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.transactions.toggle', $tx) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold cursor-pointer transition-colors
                                    {{ $tx->status === 'lunas'
                                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-400'
                                        : 'bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-400' }}"
                                title="Klik ubah status">
                                @if($tx->status === 'lunas')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                    !&nbsp;
                                @endif
                                {{ $tx->status === 'lunas' ? 'Lunas' : 'Belum' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.transactions.show', $tx) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-brand hover:text-brand transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <div class="inline-flex flex-col items-center gap-2 text-slate-400 dark:text-slate-500">
                            <div class="p-3 bg-slate-100 dark:bg-slate-700/50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/></svg>
                            </div>
                            <p class="text-sm">Tidak ada transaksi hari ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    
    {{-- Total Semua Transaksi --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-4">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">TOTAL TRANSAKSI</p>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono mt-3">{{ $totalTransactions }}</p>
            </div>
            <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 rounded-2xl flex items-center justify-center text-4xl transition-transform hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                </svg>
            </div>
        </div>
        <p class="text-xs text-slate dark:text-slate-400 mt-4">Semua transaksi sejak awal</p>
    </div>

    {{-- Total Revenue --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">TOTAL REVENUE</p>
                <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono mt-3">
                    @php
                        $totalRevK = $totalRev >= 1000000 
                            ? number_format($totalRev / 1000000, 1) . 'jt' 
                            : ($totalRev >= 1000 ? number_format($totalRev / 1000) . 'rb' : number_format($totalRev));
                    @endphp
                    {{ $totalRevK }}
                </p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 rounded-2xl flex items-center justify-center text-4xl transition-transform hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 4.01V8" />
                </svg>
            </div>
        </div>
        <p class="text-xs text-slate dark:text-slate-400 mt-4">Rp {{ number_format($totalRev, 0, ',', '.') }}</p>
    </div>

    {{-- Belum Lunas --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">BELUM LUNAS</p>
                <p class="text-5xl font-extrabold text-red-600 dark:text-red-400 font-mono mt-3">{{ $belumLunas }}</p>
            </div>
            <div class="w-14 h-14 bg-red-100 dark:bg-red-900/40 text-red-600 rounded-2xl flex items-center justify-center text-4xl transition-transform hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
        <p class="text-xs text-slate dark:text-slate-400 mt-4">Transaksi menunggu pelunasan</p>
    </div>
</div>
@endsection