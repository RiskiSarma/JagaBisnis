@extends('layouts.app')
@section('title', 'Customer')
@section('content')

<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Customer</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $customers->count() }} pelanggan</p>
    </div>
    <button type="button" onclick="bukaModalCustomer()"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah
    </button>
</div>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">No. HP</th>
                    <th class="px-5 py-3">Kunjungan</th>
                    <th class="px-5 py-3">Total Belanja</th>
                    <th class="px-5 py-3">Terakhir</th>
                    <th class="px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($customers as $i => $customer)
                @php
                    $waNum   = preg_replace('/[^0-9]/', '', preg_replace('/^0/', '62', $customer->phone ?? ''));
                    $bizName = auth()->user()->business?->name ?? 'Toko';
                    $bgColors = ['from-emerald-500 to-emerald-600','from-blue-500 to-blue-600','from-violet-500 to-purple-600','from-rose-500 to-pink-600','from-amber-500 to-orange-500'];
                    $bg = $bgColors[$i % count($bgColors)];
                    $lastVisit = $customer->last_visit ?? null;
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br {{ $bg }} flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-slate-800 dark:text-white">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        @if($customer->phone)
                        <a href="https://wa.me/{{ $waNum }}" target="_blank"
                           class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-semibold hover:underline text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                            {{ $customer->phone }}
                        </a>
                        @else
                        <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400">
                            {{ $customer->visits ?? 0 }}x
                        </span>
                    </td>
                    <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">Rp {{ number_format($customer->total_spend ?? 0, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400 text-xs">
                        {{ $lastVisit ? \Carbon\Carbon::parse($lastVisit)->locale('id')->isoFormat('D MMM YYYY') : '-' }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5">
                            <button type="button"
                                onclick="bukaFollowUp('{{ addslashes($customer->name) }}', '{{ $customer->phone ?? '' }}', '{{ $waNum }}', '{{ addslashes($bizName) }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-blue-500 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                Follow Up
                            </button>
                            @if($customer->phone)
                            <a href="https://wa.me/{{ $waNum }}" target="_blank"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#25D366] hover:bg-[#1fba59] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                            </a>
                            @endif
                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Hapus customer ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-slate-200 dark:border-slate-600 text-slate-400 hover:border-red-400 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada customer.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Customer --}}
<div id="custOverlay"
     class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this)tutupModalCustomer()">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-sm p-6">
        <h3 class="text-base font-extrabold text-slate-800 dark:text-white mb-5">Tambah Customer</h3>
        <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nama</label>
                <input type="text" name="name" required placeholder="Nama pelanggan"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">No. HP</label>
                <input type="text" name="phone" placeholder="0812-xxxx-xxxx"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="tutupModalCustomer()"
                    class="px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Follow Up (sama seperti Lap. Customer) --}}
<div id="fuOverlay"
     class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this)tutupFollowUp()">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-lg">
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
                    @if(($activePromos ?? collect())->count())
                    <optgroup label="Promo Aktif">
                        @foreach($activePromos as $promo)
                        <option value="promo_{{ $promo->id }}">🎁 Promo: {{ $promo->name }}</option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Isi Pesan</label>
                <textarea id="fuMsg" rows="5"
                    placeholder="Ketik pesan di sini, atau pilih template di atas..."
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 resize-none leading-relaxed"></textarea>
            </div>
            <p class="text-xs text-slate-400 mb-5">💡 Pesan akan dibuka di WhatsApp Web / App. Anda bisa edit sebelum mengirim.</p>
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
function bukaModalCustomer() { document.getElementById('custOverlay').classList.remove('hidden'); }
function tutupModalCustomer() { document.getElementById('custOverlay').classList.add('hidden'); }

var _fuPhone = '', _fuWaNum = '', _fuName = '', _fuBiz = '';

var PROMO_DATA = {
@foreach(($activePromos ?? collect()) as $promo)
    {{ $promo->id }}: {
        name: @json($promo->name),
        desc: @json($promo->description ?? ''),
        type: @json($promo->type ?? 'percent'),
        value: {{ $promo->value ?? 0 }},
        minBuy: {{ $promo->min_buy ?? 0 }},
        code: @json($promo->code ?? ''),
    },
@endforeach
};

function bukaFollowUp(name, phone, waNum, biz) {
    _fuName = name; _fuPhone = phone; _fuWaNum = waNum; _fuBiz = biz;
    document.getElementById('fuName').textContent  = name;
    document.getElementById('fuPhone').textContent = phone || '(no HP)';
    document.getElementById('fuTemplate').value = '';
    document.getElementById('fuMsg').value = '';
    document.getElementById('fuOverlay').classList.remove('hidden');
}
function tutupFollowUp() { document.getElementById('fuOverlay').classList.add('hidden'); }

function fillTemplate() {
    var tpl = document.getElementById('fuTemplate').value;
    var msgs = {
        thanks:   'Halo ' + _fuName + '! 👋\n\nTerima kasih sudah berkunjung ke *' + _fuBiz + '*. Kami sangat senang bisa melayani Anda!\n\nSampai jumpa lagi ya 😊',
        reminder: 'Halo ' + _fuName + '! 👋\n\nSudah lama tidak bertemu nih! Kami rindu kunjungan Anda di *' + _fuBiz + '* 😊\n\nAda yang baru lho, yuk mampir lagi!',
        custom: '',
    };
    if (tpl.indexOf('promo_') === 0) {
        var promo = PROMO_DATA[tpl.replace('promo_','')];
        if (promo) {
            var disc = promo.type === 'percent' ? 'diskon ' + promo.value + '%' : 'potongan Rp' + promo.value.toLocaleString('id-ID');
            document.getElementById('fuMsg').value =
                'Halo ' + _fuName + '! 🎉\n\nAda promo spesial dari *' + _fuBiz + '* untuk Anda!\n\n'
                + '🏷️ *' + promo.name + '*\n'
                + (promo.desc ? promo.desc + '\n' : '')
                + '💰 ' + disc
                + (promo.minBuy ? '\n🛒 Min. pembelian Rp' + promo.minBuy.toLocaleString('id-ID') : '')
                + (promo.code ? '\n🔑 Kode: *' + promo.code + '*' : '')
                + '\n\nJangan sampai terlewat ya! 😊';
        }
        return;
    }
    if (msgs[tpl] !== undefined) document.getElementById('fuMsg').value = msgs[tpl];
}

function kirimFollowUp() {
    var msg = document.getElementById('fuMsg').value.trim();
    if (!msg) { alert('Tulis pesan terlebih dahulu.'); return; }
    if (!_fuWaNum) { alert('Pelanggan tidak memiliki nomor HP.'); return; }
    tutupFollowUp();
    window.open('https://wa.me/' + _fuWaNum + '?text=' + encodeURIComponent(msg), '_blank');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { tutupModalCustomer(); tutupFollowUp(); }
});

@if($errors->any())
    bukaModalCustomer();
@endif
</script>
@endpush

@endsection