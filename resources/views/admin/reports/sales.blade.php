@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('content')

{{-- Header --}}
<div class="page-header mb-5">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Laporan Penjualan</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pantau performa penjualan berdasarkan periode.</p>
    </div>
</div>

{{-- Tab Filter --}}
<div class="flex border-b border-slate-200 dark:border-slate-700 mb-5 overflow-x-auto">
    @foreach(['harian' => 'Harian', 'bulanan' => 'Bulanan', 'tahunan' => 'Tahunan'] as $val => $lbl)
    <a href="{{ route('admin.reports.sales', ['period' => $val]) }}"
       class="px-5 py-2.5 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors -mb-px
           {{ $period === $val
               ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400'
               : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
        {{ $lbl }}
    </a>
    @endforeach
</div>

{{-- Stat Cards --}}
@php
    $totLunas = $data->sum('lunas');
    $totBelum = $data->sum('belum');
    $avgRev   = $data->count() ? round($totalRev / $data->count()) : 0;
    $fmtK = fn($n) => $n >= 1000000 ? round($n/1000000,1).'jt' : ($n >= 1000 ? round($n/1000).'rb' : $n);
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-blue-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/><line x1="9" y1="9" x2="15" y2="9" stroke-width="1.75"/><line x1="9" y1="13" x2="15" y2="13" stroke-width="1.75"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Transaksi</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalTx) }}</p>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-emerald-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23" stroke-width="1.75" stroke-linecap="round"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-width="1.75" stroke-linecap="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Revenue</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $fmtK($totalRev) }}</p>
        <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($totalRev, 0, ',', '.') }}</p>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10" stroke-width="1.75" stroke-linecap="round"/><line x1="12" y1="20" x2="12" y2="4" stroke-width="1.75" stroke-linecap="round"/><line x1="6" y1="20" x2="6" y2="14" stroke-width="1.75" stroke-linecap="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Rata-rata / Periode</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $fmtK($avgRev) }}</p>
        <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($avgRev, 0, ',', '.') }}</p>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-emerald-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Lunas</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totLunas) }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $totBelum }} belum lunas</p>
    </div>

</div>

{{-- Bar Chart --}}
@if($data->count())
@php $maxRev = $data->max('revenue') ?: 1; @endphp
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 mb-5">
    <h3 class="text-sm font-bold text-slate-700 dark:text-white mb-0.5">Grafik Revenue</h3>
    <p class="text-xs text-slate-400 mb-4">Per {{ $period === 'harian' ? 'hari' : ($period === 'bulanan' ? 'bulan' : 'tahun') }}</p>
    <div class="flex items-end gap-2" style="height:110px">
        @foreach($data->sortBy('label')->take(10) as $item)
        @php
            $rev   = data_get($item, 'revenue', 0);
            $label = data_get($item, 'label', '-');
            $pct   = round($rev / $maxRev * 100);
            $short = strlen($label) > 7 ? substr($label, 5) : $label;
            $h     = max(4, round($pct * 1.0));
        @endphp
        <div class="flex-1 flex flex-col items-center gap-1 min-w-0">
            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">
                {{ $rev >= 1000000 ? round($rev/1000000,1).'jt' : ($rev >= 1000 ? round($rev/1000).'rb' : $rev) }}
            </span>
            <div class="w-full rounded-t-sm bg-blue-600" style="height:{{ $h }}px;min-height:4px"></div>
            <span class="text-[9px] text-slate-400 truncate w-full text-center">{{ $short }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Tabel Rincian --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
        <h3 class="text-sm font-bold text-slate-700 dark:text-white">Rincian</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-5 py-3">{{ $period === 'harian' ? 'Tanggal' : ($period === 'bulanan' ? 'Bulan' : 'Tahun') }}</th>
                    <th class="px-5 py-3">Transaksi</th>
                    <th class="px-5 py-3">Revenue</th>
                    <th class="px-5 py-3">Rata-rata</th>
                    <th class="px-5 py-3">Lunas</th>
                    <th class="px-5 py-3">Belum</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($data as $item)
                @php
                    $rev   = data_get($item, 'revenue', 0);
                    $count = data_get($item, 'count', 0);
                    $label = data_get($item, 'label', '-');
                    $lunas = data_get($item, 'lunas', 0);
                    $belum = data_get($item, 'belum', 0);
                    $avg   = $count ? round($rev / $count) : 0;
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $label }}</td>
                    <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $count }} transaksi</td>
                    <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">Rp {{ number_format($rev, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400">Rp {{ number_format($avg, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">{{ $lunas }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold {{ $belum ? 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500' }}">{{ $belum }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada data laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection