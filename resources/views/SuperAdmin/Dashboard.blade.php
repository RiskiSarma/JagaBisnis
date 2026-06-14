@extends('layouts.app')
@section('title', 'Dashboard Global')
@section('content')

<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Dashboard Global</h2>
        <p class="text-sm text-slate-400 mt-0.5">Pantau semua bisnis dalam satu platform</p>
    </div>
    <button onclick="document.getElementById('modalTambahBisnis').classList.remove('hidden')"
            class="flex items-center gap-2 bg-brand hover:bg-brand-dark text-white px-4 py-2 rounded-xl text-sm font-bold transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19" stroke-width="2"/><line x1="5" y1="12" x2="19" y2="12" stroke-width="2"/></svg>
        Bisnis Baru
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    @php
        $stats = [
            ['label'=>'Total Bisnis','value'=>$businesses->count(),'sub'=>$businesses->where('status','active')->count().' aktif','color'=>'#1A56DB'],
            ['label'=>'Total Transaksi','value'=>$totalTx,'sub'=>'','color'=>'#10B981'],
            ['label'=>'Total Revenue','value'=>'Rp '.number_format($totalRevenue,0,',','.'),'sub'=>'','color'=>'#F59E0B'],
            ['label'=>'Total Pengguna','value'=>$totalUsers,'sub'=>'','color'=>'#8B5CF6'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-5 hover:-translate-y-0.5 transition-transform" style="border-top:3px solid {{$s['color']}}">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{$s['label']}}</p>
        <p class="text-2xl font-extrabold text-slate-900 dark:text-white font-mono">{{$s['value']}}</p>
        @if($s['sub'])<p class="text-xs text-emerald-500 font-semibold mt-1">{{$s['sub']}}</p>@endif
    </div>
    @endforeach
</div>

{{-- Revenue Chart --}}
@php $maxRev = $businesses->max('total_revenue') ?: 1; @endphp
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-5 mb-5">
    <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-1">Revenue per Bisnis</h3>
    <p class="text-xs text-slate-400 mb-4">Performa finansial masing-masing toko</p>
    <div class="flex items-end gap-6 h-28">
        @php $colors = ['#1A56DB','#10B981','#F59E0B','#8B5CF6','#EF4444'] @endphp
        @foreach($businesses as $i => $b)
        <div class="flex-1 flex flex-col items-center gap-1">
            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $b->total_revenue >= 1000000 ? number_format($b->total_revenue/1000000,1).'jt' : ($b->total_revenue >= 1000 ? number_format($b->total_revenue/1000,0).'rb' : $b->total_revenue) }}</span>
            <div class="w-full rounded-t-lg min-h-1" style="height:{{ round($b->total_revenue/$maxRev*100) }}%;background:{{ $colors[$i%5] }}"></div>
            <span class="text-[9px] text-slate-400 font-semibold text-center">{{ Str::words($b->name,1,'') }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Tabel Bisnis --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
        <h3 class="font-bold text-sm text-slate-800 dark:text-white">Semua Bisnis</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 dark:bg-slate-900/50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <th class="text-left px-5 py-3">Bisnis</th>
                <th class="text-left px-5 py-3">Tipe</th>
                <th class="text-left px-5 py-3">Transaksi</th>
                <th class="text-left px-5 py-3">Revenue</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-left px-5 py-3">Fitur Stok</th>
                <th class="text-left px-5 py-3">Toggle</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @foreach($businesses as $b)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $b->name }}</td>
                <td class="px-5 py-3"><span class="bg-brand/10 text-brand text-xs font-bold px-2 py-0.5 rounded-lg">{{ $b->type }}</span></td>
                <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $b->total_transactions }}</td>
                <td class="px-5 py-3 font-bold font-mono text-slate-800 dark:text-white">Rp {{ number_format($b->total_revenue,0,',','.') }}</td>
                <td class="px-5 py-3">
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $b->status==='active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-700' }}">
                        {{ $b->status==='active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $b->feat_stok ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                        {{ $b->feat_stok ? '✓ Aktif' : '— Off' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <form method="POST" action="{{ route('sa.businesses.toggle-status', $b) }}" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs {{ $b->status==='active' ? 'bg-emerald-500 hover:bg-red-500' : 'bg-slate-300 hover:bg-emerald-500' }} text-white px-3 py-1 rounded-lg font-bold transition-colors">
                            {{ $b->status==='active' ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Bisnis --}}
<div id="modalTambahBisnis" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6">
            <h3 class="text-base font-extrabold mb-4">Tambah Bisnis Baru</h3>
            <form method="POST" action="{{ route('sa.businesses.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nama Bisnis</label>
                        <input name="name" required placeholder="cth: Kopi Jaya" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-brand bg-white dark:bg-slate-700"></div>
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Tipe</label>
                        <select name="type" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 outline-none focus:border-brand">
                            <option>F&B</option><option>Retail</option><option>Laundry</option><option>Jasa</option><option>Lainnya</option>
                        </select></div>
                </div>
                <hr class="border-slate-100 dark:border-slate-700">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Akun Manager</p>
                <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nama Manager</label>
                    <input name="mgr_name" required placeholder="Nama lengkap" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-brand bg-white dark:bg-slate-700"></div>
                <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Email Login</label>
                    <input name="mgr_email" type="email" required placeholder="manager@toko.com" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-brand bg-white dark:bg-slate-700"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Password</label>
                        <input name="mgr_password" type="password" required placeholder="Min. 6 karakter" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-brand bg-white dark:bg-slate-700"></div>
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Konfirmasi</label>
                        <input name="mgr_password_confirmation" type="password" required placeholder="Ulangi" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-brand bg-white dark:bg-slate-700"></div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalTambahBisnis').classList.add('hidden')"
                            class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-brand text-white rounded-xl text-sm font-bold hover:bg-brand-dark">Buat Bisnis & Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection