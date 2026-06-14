@extends('layouts.app')
@section('title', 'Laporan Customer')
@section('content')

<div class="mb-5">
    <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Laporan Customer</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Customer terbaik berdasarkan pengeluaran.</p>
</div>

{{-- Tab Filter --}}
<div class="flex gap-2 flex-wrap mb-5">
    @foreach([
        'spending' => ['icon'=>'M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6','lbl'=>'Spending Tertinggi'],
        'visits'   => ['icon'=>'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75','lbl'=>'Paling Sering'],
        'recent'   => ['icon'=>'M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z','lbl'=>'Terbaru'],
    ] as $val => $item)
    <a href="{{ route('admin.reports.customers', ['sort' => $val]) }}"
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
    $totSpend = $data->sum('total_spend');
    $avg      = $data->count() ? round($totSpend / $data->count()) : 0;
    $top      = $data->first();
    $fmtK = fn($n) => $n >= 1000000 ? round($n/1000000,1).'jt' : ($n >= 1000 ? round($n/1000).'rb' : $n);
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-blue-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4" stroke-width="1.75"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-width="1.75"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Customer</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $data->count() }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-emerald-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23" stroke-width="1.75" stroke-linecap="round"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke-width="1.75" stroke-linecap="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Total Spending</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $fmtK($totSpend) }}</p>
        <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($totSpend, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10" stroke-width="1.75" stroke-linecap="round"/><line x1="12" y1="20" x2="12" y2="4" stroke-width="1.75" stroke-linecap="round"/><line x1="6" y1="20" x2="6" y2="14" stroke-width="1.75" stroke-linecap="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Rata-rata Spending</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $fmtK($avg) }}</p>
        <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($avg, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-violet-600/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10" stroke-width="1.75" stroke-linecap="round"/><line x1="12" y1="20" x2="12" y2="4" stroke-width="1.75" stroke-linecap="round"/><line x1="6" y1="20" x2="6" y2="14" stroke-width="1.75" stroke-linecap="round"/></svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Customer Loyal</p>
        <p class="text-lg font-extrabold text-slate-900 dark:text-white truncate">{{ $top?->name ?? '—' }}</p>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
        <h3 class="text-sm font-bold text-slate-700 dark:text-white">Rincian Customer</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-5 py-3">Rank</th>
                    <th class="px-5 py-3">Customer</th>
                    <th class="px-5 py-3">No. HP</th>
                    <th class="px-5 py-3">Kunjungan</th>
                    <th class="px-5 py-3">Total Spending</th>
                    <th class="px-5 py-3">Avg/Kunjungan</th>
                    <th class="px-5 py-3">Terakhir</th>
                    <th class="px-5 py-3">Tier</th>
                    <th class="px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($data as $i => $cust)
                @php
                    $spend     = $cust->total_spend ?? 0;
                    $visits    = $cust->visits ?? 0;
                    $lastVisit = $cust->last_visit
                        ? \Carbon\Carbon::parse($cust->last_visit)->locale('id')->isoFormat('D MMM YYYY')
                        : '-';
                    $avgVisit  = $visits ? round($spend / $visits) : 0;
                    $tier      = $spend >= 500000 ? '🥇 VIP' : ($spend >= 200000 ? '🥈 Reguler' : '🥉 Baru');
                    $rankColors = ['#F59E0B','#64748B','#B45309'];
                    $rankColor  = $rankColors[$i] ?? '#94A3B8';
                    $bgColors   = ['from-violet-500 to-purple-600','from-blue-500 to-blue-600','from-emerald-500 to-emerald-600','from-rose-500 to-pink-600','from-amber-500 to-orange-500'];
                    $bg         = $bgColors[$i % count($bgColors)];
                    $waNum      = preg_replace('/[^0-9]/', '', preg_replace('/^0/', '62', $cust->phone ?? ''));
                    $bizName    = auth()->user()->business?->name ?? 'Toko';
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-5 py-3">
                        <span class="font-extrabold text-sm" style="color:{{ $rankColor }}">#{{ $i + 1 }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br {{ $bg }} flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($cust->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-slate-800 dark:text-white">{{ $cust->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        @if($cust->phone)
                        <a href="https://wa.me/{{ $waNum }}" target="_blank"
                           class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-semibold hover:underline text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                            {{ $cust->phone }}
                        </a>
                        @else
                        <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400">
                            {{ $visits }}x
                        </span>
                    </td>
                    <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">Rp {{ number_format($spend, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400">Rp {{ number_format($avgVisit, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $lastVisit }}</td>
                    <td class="px-5 py-3 text-sm">{{ $tier }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5">
                            <button type="button"
                                onclick="bukaFollowUp('{{ addslashes($cust->name) }}', '{{ $cust->phone ?? '' }}', '{{ $waNum }}', '{{ addslashes($bizName) }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-blue-500 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                Follow Up
                            </button>
                            @if($cust->phone)
                            <a href="https://wa.me/{{ $waNum }}" target="_blank"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#25D366] hover:bg-[#1fba59] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada data customer.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Follow Up --}}
<div id="fuOverlay"
     class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this)tutupFollowUp()">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-lg">

        {{-- Header --}}
        <div class="flex items-center gap-4 p-6 pb-5">
            <div class="w-12 h-12 rounded-full bg-[#25D366] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Follow Up via WhatsApp</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Kepada: <strong id="fuName" class="text-slate-700 dark:text-slate-200"></strong>
                    &bull; <span id="fuPhone" class="text-slate-500"></span>
                </p>
            </div>
        </div>

        <div class="px-6 pb-6">
            {{-- Template --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Template Pesan</label>
                <select id="fuTemplate" onchange="fillTemplate()"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500">
                    <option value="">— Pilih template atau tulis sendiri —</option>
                    <optgroup label="Umum">
                        <option value="thanks">✅ Terima Kasih Sudah Berkunjung</option>
                        <option value="reminder">🔔 Sudah Lama Tidak Berkunjung</option>
                        <option value="custom">✏️ Pesan Bebas</option>
                    </optgroup>
                    @if($activePromos->count())
                    <optgroup label="Promo Aktif">
                        @foreach($activePromos as $promo)
                        <option value="promo_{{ $promo->id }}">🎁 Promo: {{ $promo->name }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
            </div>

            {{-- Isi Pesan --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Isi Pesan</label>
                <textarea id="fuMsg" rows="5"
                    placeholder="Ketik pesan di sini, atau pilih template di atas..."
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 resize-none leading-relaxed"></textarea>
            </div>

            <p class="text-xs text-slate-400 mb-5">
                💡 Pesan akan dibuka di WhatsApp Web / App. Anda bisa edit sebelum mengirim.
            </p>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <button type="button" onclick="tutupFollowUp()"
                    class="px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="kirimFollowUp()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl bg-[#25D366] hover:bg-[#1fba59] text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    Buka WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
var _fuPhone = '', _fuWaNum = '', _fuName = '', _fuBiz = '';

function bukaFollowUp(name, phone, waNum, biz) {
    _fuName  = name;
    _fuPhone = phone;
    _fuWaNum = waNum;
    _fuBiz   = biz;

    document.getElementById('fuName').textContent    = name;
    document.getElementById('fuPhone').textContent   = phone || '(no HP)';
    document.getElementById('fuTemplate').value      = '';
    document.getElementById('fuMsg').value           = '';
    document.getElementById('fuOverlay').classList.remove('hidden');
}

function tutupFollowUp() {
    document.getElementById('fuOverlay').classList.add('hidden');
}

function fillTemplate() {
    var tpl = document.getElementById('fuTemplate').value;
    var msgs = {
        thanks:   'Halo ' + _fuName + '! 👋\n\nTerima kasih sudah berkunjung ke *' + _fuBiz + '*. Kami sangat senang bisa melayani Anda!\n\nSampai jumpa lagi ya 😊',
        reminder: 'Halo ' + _fuName + '! 👋\n\nSudah lama tidak bertemu nih! Kami rindu kunjungan Anda di *' + _fuBiz + '* 😊\n\nAda yang baru lho, yuk mampir lagi!',
        custom:   '',
    };
    if (msgs[tpl] !== undefined) {
        document.getElementById('fuMsg').value = msgs[tpl];
    }
}

function kirimFollowUp() {
    var msg = document.getElementById('fuMsg').value.trim();
    if (!msg) {
        alert('Tulis pesan terlebih dahulu.');
        return;
    }
    if (!_fuWaNum) {
        alert('Pelanggan tidak memiliki nomor HP.');
        return;
    }
    tutupFollowUp();
    window.open('https://wa.me/' + _fuWaNum + '?text=' + encodeURIComponent(msg), '_blank');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupFollowUp();
});
</script>
@endpush

@endsection