@extends('layouts.app')
@section('title', 'Laporan Produk')
@section('content')

<div class="mb-5">
    <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Laporan Produk</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Produk terlaris dan performa penjualan.</p>
</div>

{{-- Tab Filter --}}
<div class="flex gap-2 flex-wrap mb-5">
    @foreach(['laris' => ['icon'=>'M18 20V10M12 20V4M6 20v-6','lbl'=>'Terlaris'], 'kurang' => ['icon'=>'M18 20V10M12 20V4M6 20v-6','lbl'=>'Kurang Laku'], 'revenue' => ['icon'=>'M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6','lbl'=>'Revenue Tertinggi']] as $val => $item)
    <a href="{{ route('admin.reports.products', ['sort' => $val]) }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold border transition-colors
           {{ $sort === $val
               ? 'bg-blue-600 text-white border-blue-600'
               : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400 hover:text-blue-600' }}">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $item['icon'] }}"/></svg>
        {{ $item['lbl'] }}
    </a>
    @endforeach
</div>

{{-- Stat Cards --}}
@php
    $data      = collect($data);
    $totalQty  = $data->sum('count');
    $totalRev  = $data->sum('revenue');
    $topProd   = $data->first();
    $fmtK = fn($n) => $n >= 1000000 ? round($n/1000000,1).'jt' : ($n >= 1000 ? round($n/1000).'rb' : $n);
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-blue-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Produk</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $data->count() }}</p>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-emerald-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/><line x1="9" y1="9" x2="15" y2="9" stroke-width="1.75"/><line x1="9" y1="13" x2="15" y2="13" stroke-width="1.75"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Terjual</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalQty) }} pcs</p>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23" stroke-width="1.75" stroke-linecap="round"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke-width="1.75" stroke-linecap="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Revenue Produk</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $fmtK($totalRev) }}</p>
        <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($totalRev, 0, ',', '.') }}</p>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-violet-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10" stroke-width="1.75" stroke-linecap="round"/><line x1="12" y1="20" x2="12" y2="4" stroke-width="1.75" stroke-linecap="round"/><line x1="6" y1="20" x2="6" y2="14" stroke-width="1.75" stroke-linecap="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Produk Terlaris</p>
        <p class="text-lg font-extrabold text-slate-900 dark:text-white truncate">{{ $topProd ? $topProd['name'] : '—' }}</p>
    </div>

</div>

{{-- Bar Chart --}}
@if($data->count())
@php $maxQty = $data->max('count') ?: 1; @endphp
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 mb-5">
    <h3 class="text-sm font-bold text-slate-700 dark:text-white mb-0.5">Grafik Penjualan Produk</h3>
    <p class="text-xs text-slate-400 mb-4">{{ $sort === 'laris' ? 'Diurutkan dari terlaris' : ($sort === 'kurang' ? 'Diurutkan dari kurang laku' : 'Diurutkan dari revenue tertinggi') }}</p>
    <div class="flex items-end gap-2" style="height:130px">
        @foreach($data->take(8) as $item)
        @php
            $qty   = $item['count'];
            $name  = $item['name'];
            $pct   = round($qty / $maxQty * 100);
            $short = implode(' ', array_slice(explode(' ', $name), 0, 2));
            $h     = max(4, round($pct * 1.1));
        @endphp
        <div class="flex-1 flex flex-col items-center gap-1 min-w-0">
            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ $qty }}</span>
            <div class="w-full rounded-t-sm bg-blue-600" style="height:{{ $h }}px;min-height:4px"></div>
            <span class="text-[9px] text-slate-400 truncate w-full text-center">{{ $short }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Tabel --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-5 py-3">Rank</th>
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3">Terjual (qty)</th>
                    <th class="px-5 py-3">Revenue</th>
                    <th class="px-5 py-3">Performa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($data as $i => $item)
                @php
                    $qty     = $item['count'];
                    $rev     = $item['revenue'];
                    $name    = $item['name'];
                    $maxQ    = $data->max('count') ?: 1;
                    $barW    = round($qty / $maxQ * 100);
                    $rankColor = match($i) { 0 => '#F59E0B', 1 => '#94A3B8', 2 => '#B45309', default => '#94A3B8' };
                    $badge   = $qty === 0
                        ? 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400'
                        : ($qty < 3 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400'
                        : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400');
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-5 py-3">
                        <span class="font-extrabold text-sm" style="color:{{ $rankColor }}">#{{ $i + 1 }}</span>
                    </td>
                    <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $name }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badge }}">
                            {{ $qty }} pcs
                        </span>
                    </td>
                    <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">Rp {{ number_format($rev, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <div class="w-24 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $i < 3 ? 'bg-blue-600' : 'bg-slate-400' }}" style="width:{{ $barW }}%"></div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada data produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection