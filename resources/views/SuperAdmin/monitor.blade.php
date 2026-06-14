@extends('layouts.app')
@section('title', 'Monitoring')
@section('content')

{{-- Page Header --}}
<div class="flex items-start justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Monitoring Sistem</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pantau aktivitas dan status platform secara real-time.</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

    {{-- Aktivitas Hari Ini --}}
    <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 overflow-hidden hover:-translate-y-0.5 transition-transform duration-200 shadow-sm hover:shadow-md">
        <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center opacity-20" style="background:#F59E0B">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full opacity-[0.07] -translate-y-4 translate-x-4" style="background:#F59E0B"></div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 mb-2">Aktivitas Hari Ini</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono">{{ $todayActivity ?? 24 }}</p>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Total aktivitas tercatat hari ini.</p>
    </div>

    {{-- Bisnis Online --}}
    <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 overflow-hidden hover:-translate-y-0.5 transition-transform duration-200 shadow-sm hover:shadow-md">
        <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center opacity-20" style="background:#10B981">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full opacity-[0.07] -translate-y-4 translate-x-4" style="background:#10B981"></div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 mb-2">Bisnis Online</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono">{{ $onlineBiz ?? 2 }}</p>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Jumlah bisnis aktif saat ini.</p>
    </div>

    {{-- User Aktif --}}
    <div class="relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 overflow-hidden hover:-translate-y-0.5 transition-transform duration-200 shadow-sm hover:shadow-md">
        <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center opacity-20" style="background:#1A56DB">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full opacity-[0.07] -translate-y-4 translate-x-4" style="background:#1A56DB"></div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 mb-2">User Aktif</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white font-mono">{{ $activeUsers ?? 3 }}</p>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Pengguna yang sedang aktif.</p>
    </div>
</div>

{{-- Log Aktivitas Table --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden mb-5">

    {{-- Table Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex-wrap gap-3">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Log Aktivitas</h3>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" style="min-width:520px">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-[0.05em] text-slate-500 dark:text-slate-400">
                    <th class="px-4 py-3 whitespace-nowrap">Waktu</th>
                    <th class="px-4 py-3">Pengguna</th>
                    <th class="px-4 py-3">Aksi</th>
                    <th class="px-4 py-3">Bisnis</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($logs ?? [] as $log)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">

                    {{-- Waktu --}}
                    <td class="px-4 py-3 text-slate-400 dark:text-slate-500 whitespace-nowrap text-xs">
                        {{ $log['time'] ?? '-' }}
                    </td>

                    {{-- Pengguna --}}
                    <td class="px-4 py-3">
                        <span class="font-semibold text-slate-800 dark:text-white">{{ $log['user'] ?? '-' }}</span>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                        {{ $log['action'] ?? '-' }}
                    </td>

                    {{-- Bisnis --}}
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                        {{ $log['biz'] ?? '-' }}
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-4 py-3">
                        @php
                            $st = $log['status'] ?? 'info';
                            $badgeClass = match($st) {
                                'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                                'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                                default   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                            {{ $st }}
                        </span>
                    </td>
                </tr>
                @empty
                {{-- Fallback: static demo rows matching the JS reference --}}
                @php
                $demoLogs = [
                    ['time'=>'18 Apr 09:12','user'=>'Dewi Kasir',    'action'=>'Transaksi TXN-005',               'biz'=>'Kopi Nusantara','status'=>'success'],
                    ['time'=>'18 Apr 09:05','user'=>'Budi Santoso',  'action'=>'Tambah produk baru',              'biz'=>'Kopi Nusantara','status'=>'success'],
                    ['time'=>'18 Apr 08:55','user'=>'Andi Laundry',  'action'=>'Login ke sistem',                 'biz'=>'Laundry Bersih','status'=>'info'],
                    ['time'=>'18 Apr 08:30','user'=>'Super Admin',   'action'=>'Nonaktifkan Toko Serba Ada',      'biz'=>'Platform',      'status'=>'warning'],
                    ['time'=>'17 Apr 17:22','user'=>'Dewi Kasir',    'action'=>'Transaksi TXN-003',               'biz'=>'Kopi Nusantara','status'=>'success'],
                ];
                @endphp
                @foreach($demoLogs as $log)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-4 py-3 text-slate-400 dark:text-slate-500 whitespace-nowrap text-xs">{{ $log['time'] }}</td>
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">{{ $log['user'] }}</td>
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $log['action'] }}</td>
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $log['biz'] }}</td>
                    <td class="px-4 py-3">
                        @php
                            $badgeClass = match($log['status']) {
                                'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                                'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                                default   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                            {{ $log['status'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection