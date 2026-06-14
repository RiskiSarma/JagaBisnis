@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')

{{-- Page Header --}}
<div class="flex items-start justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Transaksi</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $counts['all'] }} total transaksi</p>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="flex flex-wrap gap-2 mb-5">
    <a href="{{ route('admin.transactions.index', ['filter'=>'all']) }}"
        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
            {{ $filter==='all' ? 'bg-brand text-white shadow-sm' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-brand hover:text-brand' }}">
        Semua ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.transactions.index', ['filter'=>'lunas']) }}"
        class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
            {{ $filter==='lunas' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-emerald-500 hover:text-emerald-600' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Lunas ({{ $counts['lunas'] }})
    </a>
    <a href="{{ route('admin.transactions.index', ['filter'=>'belum_lunas']) }}"
        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
            {{ $filter==='belum_lunas' ? 'bg-red-500 text-white shadow-sm' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-red-400 hover:text-red-500' }}">
        ! Belum Lunas ({{ $counts['belum_lunas'] }})
    </a>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" style="min-width:680px">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-[0.05em] text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Tgl / Waktu</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">No. HP</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Kasir</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($transactions as $tx)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-4 py-3">
                        <code class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded">
                            TXN-{{ str_pad($tx->id,3,'0',STR_PAD_LEFT) }}
                        </code>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $tx->created_at->format('d M Y') }}</div>
                        <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $tx->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">
                        {{ $tx->customer?->name ?? 'Pelanggan' }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                        @if($tx->customer?->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',preg_replace('/^0/','62',$tx->customer->phone)) }}"
                               target="_blank" class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                {{ $tx->customer->phone }}
                            </a>
                        @else
                            <span class="text-slate-400 dark:text-slate-500">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                        @php
                            $raw = $tx->getRawOriginal('items');
                            $cnt = is_string($raw) ? count(json_decode($raw,true)??[]) : (is_array($tx->items)?count($tx->items):0);
                        @endphp
                        {{ $cnt }} item
                    </td>
                    <td class="px-4 py-3 font-bold text-slate-900 dark:text-white font-mono">
                        Rp {{ number_format($tx->total,0,',','.') }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500">{{ $tx->kasir?->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.transactions.toggle', $tx) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold cursor-pointer transition-colors
                                    {{ $tx->status==='lunas'
                                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-400'
                                        : 'bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-400' }}"
                                title="Klik ubah status">
                                @if($tx->status==='lunas')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Lunas
                                @else
                                    ! Belum Lunas
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <button onclick="openTxModal({{ $tx->id }})"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-brand hover:text-brand transition-colors whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Lihat
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-16 text-center">
                        <div class="inline-flex flex-col items-center gap-3 text-slate-400 dark:text-slate-500">
                            <div class="p-4 bg-slate-100 dark:bg-slate-700/50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/></svg>
                            </div>
                            <p class="text-sm">Belum ada transaksi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
        {{ $transactions->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- ══ MODAL: Detail Transaksi ══ --}}
<div id="modal-tx-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100 dark:border-slate-700">
            <h3 id="modal-tx-title" class="text-base font-extrabold text-slate-800 dark:text-slate-100">
                Detail Transaksi
            </h3>
            <button onclick="closeTxModal()"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-5 space-y-4">

            {{-- ── Struk ── --}}
            <div id="receipt-area"
                class="border border-dashed border-slate-200 dark:border-slate-600 rounded-xl p-5 bg-slate-50 dark:bg-slate-900/50">
                {{-- Logo + Biz --}}
                <div class="text-center mb-4">
                    <img src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png"
                         alt="" class="h-8 mx-auto mb-2 object-contain" onerror="this.style.display='none'">
                    <p id="rx-biz-name" class="text-base font-extrabold text-slate-800 dark:text-white">—</p>
                    <p id="rx-meta" class="text-xs text-blue-500 mt-0.5">—</p>
                    <p id="rx-phone-line" class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 hidden">—</p>
                </div>

                <hr class="border-dashed border-slate-200 dark:border-slate-600 mb-3">

                {{-- Items --}}
                <div id="rx-items" class="space-y-1.5 mb-3"></div>

                <hr class="border-dashed border-slate-200 dark:border-slate-600 mb-3">

                {{-- Discount --}}
                <div id="rx-discount" class="hidden justify-between text-xs text-emerald-600 dark:text-emerald-400 mb-1.5">
                    <span>Diskon</span>
                    <span id="rx-discount-val" class="font-semibold"></span>
                </div>

                {{-- Total --}}
                <div class="flex justify-between font-extrabold text-slate-900 dark:text-white">
                    <span class="text-base">TOTAL</span>
                    <span id="rx-total" class="font-mono text-lg">—</span>
                </div>

                {{-- Cash change --}}
                <div id="rx-change-wrap" class="hidden mt-2 space-y-1">
                    <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Uang Diterima</span><span id="rx-received" class="font-mono"></span>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Kembalian</span><span id="rx-change" class="font-mono"></span>
                    </div>
                </div>

                <hr class="border-dashed border-slate-200 dark:border-slate-600 my-3">

                <p id="rx-customer-line" class="text-center text-xs text-slate-400 dark:text-slate-500">—</p>
                <p class="text-center text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-2">
                    🙏 Terima kasih sudah berbelanja!
                </p>
            </div>

            {{-- ── Catatan ── --}}
            <div>
                <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Catatan
                </label>
                <form id="form-save-catatan" method="POST">
                    @csrf @method('PATCH')
                    <textarea id="modal-catatan" name="catatan" rows="2"
                        placeholder="Tambah catatan transaksi..."
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand resize-none transition-all"></textarea>
                </form>
            </div>

            {{-- ── WA Section (hanya tampil jika ada no HP) ── --}}
            <div id="wa-section" class="hidden space-y-2">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl p-4">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-full bg-[#25D366] flex items-center justify-center shrink-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-300">Kirim Struk via WhatsApp</p>
                            <p id="wa-phone-label" class="text-xs text-emerald-600 dark:text-emerald-400">—</p>
                        </div>
                    </div>
                    <input type="text" id="wa-extra-msg"
                        placeholder="Ucapan / pesan tambahan ke pelanggan (opsional)..."
                        class="w-full px-3 py-2.5 border border-emerald-200 dark:border-emerald-700 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400 transition-all">
                </div>
                <button onclick="kirimLinkStrukWA()"
                    class="w-full inline-flex items-center justify-center gap-2 py-3 bg-[#25D366] hover:bg-[#1fba59] text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    Kirim Link Struk via WA
                </button>
            </div>

        </div>{{-- end modal body --}}

        {{-- Modal Footer --}}
        <div class="flex items-center justify-between px-6 pb-5 gap-3 flex-wrap border-t border-slate-100 dark:border-slate-700 pt-4">
            <div class="flex gap-2">
                <button onclick="printTxReceipt()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl hover:border-brand hover:text-brand transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak
                </button>
                <button onclick="printTxReceipt()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl hover:border-emerald-500 hover:text-emerald-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="12" y2="18"/><line x1="15" y1="15" x2="12" y2="18"/></svg>
                    PDF
                </button>
            </div>
            <button onclick="closeTxModal()"
                class="px-5 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                Tutup
            </button>
        </div>

    </div>
</div>

{{-- ── TX DATA untuk JavaScript ── --}}
<script>
const TX_DATA = {
    @foreach($transactions as $tx)
    {{ $tx->id }}: {
        id:           {{ $tx->id }},
        biz:          "{{ addslashes($tx->business?->name ?? '') }}",
        date:         "{{ $tx->created_at->format('Y-m-d') }}",
        time:         "{{ $tx->created_at->format('H:i') }}",
        kasir:        "{{ addslashes($tx->kasir?->name ?? '-') }}",
        customer:     "{{ addslashes($tx->customer?->name ?? 'Pelanggan') }}",
        phone:        "{{ $tx->customer?->phone ?? '' }}",
        items:        {!! json_encode(is_string($tx->getRawOriginal('items')) ? json_decode($tx->getRawOriginal('items'),true) : (is_array($tx->items)?$tx->items:[])) !!},
        discount:     {{ $tx->discount ?? 0 }},
        total:        {{ $tx->total }},
        pay_method:   "{{ $tx->pay_method ?? 'cash' }}",
        cash_received:{{ $tx->cash_received ?? 0 }},
        cash_change:  {{ $tx->cash_change ?? 0 }},
        status:       "{{ $tx->status }}",
        catatan:      "{{ addslashes($tx->catatan ?? '') }}",
        catatan_url:  "{{ route('admin.transactions.update', $tx) }}",
    },
    @endforeach
};

// ── Helpers ──
function fmt(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}
function waNum(phone) {
    return phone.replace(/[\s\-().]/g,'').replace(/^0/,'62').replace(/^\+/,'');
}

// ── Buka Modal ──
function openTxModal(id) {
    const tx = TX_DATA[id];
    if (!tx) return;

    // Title
    document.getElementById('modal-tx-title').textContent =
        'Detail Transaksi TXN-' + String(id).padStart(3, '0');

    // Biz name & meta
    document.getElementById('rx-biz-name').textContent = tx.biz;
    document.getElementById('rx-meta').textContent =
        tx.date + ' • Kasir: ' + tx.kasir;

    // Phone line di struk (tampil jika ada no HP)
    const phoneLine = document.getElementById('rx-phone-line');
    if (tx.phone) {
        phoneLine.textContent = 'HP: ' + tx.phone;
        phoneLine.classList.remove('hidden');
    } else {
        phoneLine.classList.add('hidden');
    }

    // Items
    document.getElementById('rx-items').innerHTML = (tx.items || []).map(it =>
        `<div class="flex justify-between text-sm">
            <span class="text-slate-600 dark:text-slate-300">${it.name} &times;${it.qty}</span>
            <span class="font-semibold text-slate-800 dark:text-white font-mono">
                ${fmt((it.price || 0) * (it.qty || 1))}
            </span>
        </div>`
    ).join('');

    // Discount
    const discEl = document.getElementById('rx-discount');
    if (tx.discount > 0) {
        discEl.classList.remove('hidden');
        discEl.classList.add('flex');
        document.getElementById('rx-discount-val').textContent = '-' + fmt(tx.discount);
    } else {
        discEl.classList.add('hidden');
    }

    // Total
    document.getElementById('rx-total').textContent = fmt(tx.total);

    // Cash change
    const changeWrap = document.getElementById('rx-change-wrap');
    if (tx.pay_method === 'cash' && tx.cash_received > tx.total) {
        changeWrap.classList.remove('hidden');
        document.getElementById('rx-received').textContent = fmt(tx.cash_received);
        document.getElementById('rx-change').textContent   = fmt(tx.cash_change);
    } else {
        changeWrap.classList.add('hidden');
    }

    // Customer line
    document.getElementById('rx-customer-line').textContent = 'Pelanggan: ' + tx.customer;

    // Catatan
    document.getElementById('modal-catatan').value = tx.catatan || '';
    document.getElementById('form-save-catatan').action = tx.catatan_url;

    // ── WA Section: tampil hanya jika ada no HP ──
    const waSection = document.getElementById('wa-section');
    if (tx.phone) {
        document.getElementById('wa-phone-label').textContent =
            tx.phone + ' • ' + tx.customer;
        document.getElementById('wa-extra-msg').value = '';
        waSection.classList.remove('hidden');
    } else {
        waSection.classList.add('hidden');
    }

    // Simpan id aktif
    window._currentTxId = id;

    // Tampilkan modal
    document.getElementById('modal-tx-detail').classList.remove('hidden');
}

function closeTxModal() {
    document.getElementById('modal-tx-detail').classList.add('hidden');
}

// ── Kirim Link Struk via WA ──
function kirimLinkStrukWA() {
    const tx    = TX_DATA[window._currentTxId];
    if (!tx || !tx.phone) return;

    const extra   = document.getElementById('wa-extra-msg').value.trim();
    const catatan = document.getElementById('modal-catatan').value.trim();

    const itemLines = (tx.items || []).map(it =>
        `  • ${it.name} ×${it.qty}  =  Rp${((it.price||0)*(it.qty||1)).toLocaleString('id-ID')}`
    ).join('\n');

    const discLine   = tx.discount > 0 ? `\n🏷️ Diskon: -Rp${tx.discount.toLocaleString('id-ID')}` : '';
    const catatanLine = catatan ? `\n📝 Catatan: ${catatan}` : '';

    const msg =
`🧾 *NOTA BELANJA DIGITAL*
━━━━━━━━━━━━━━━━━━
🏪 *${tx.biz}*
📅 ${tx.date}  ${tx.time} WIB
🔖 No. Transaksi: TXN-${String(tx.id).padStart(3,'0')}
━━━━━━━━━━━━━━━━━━
${itemLines}
━━━━━━━━━━━━━━━━━━${discLine}
💰 *TOTAL : Rp${tx.total.toLocaleString('id-ID')}*
━━━━━━━━━━━━━━━━━━
👤 ${tx.customer}
📱 ${tx.phone}
👩‍💼 Kasir: ${tx.kasir}
✅ Status: ${tx.status}${catatanLine}
━━━━━━━━━━━━━━━━━━
${extra ? '💬 ' + extra + '\n\n' : ''}🙏 *Terima kasih sudah berbelanja di ${tx.biz}!*`;

    const num = waNum(tx.phone);
    window.open(`https://wa.me/${num}?text=${encodeURIComponent(msg)}`, '_blank');
}

// ── Cetak / PDF ──
function printTxReceipt() {
    const tx = TX_DATA[window._currentTxId];
    if (!tx) return;

    const catatan = document.getElementById('modal-catatan').value.trim();

    const itemRows = (tx.items || []).map(it =>
        `<div class="row">
            <span>${it.name} ×${it.qty}</span>
            <span>Rp${((it.price||0)*(it.qty||1)).toLocaleString('id-ID')}</span>
        </div>`
    ).join('');

    const discRow = tx.discount > 0
        ? `<div class="row disc"><span>Diskon</span><span>-Rp${tx.discount.toLocaleString('id-ID')}</span></div>`
        : '';

    const changeRows = (tx.pay_method === 'cash' && tx.cash_received > tx.total)
        ? `<div class="row"><span>Uang Diterima</span><span>Rp${tx.cash_received.toLocaleString('id-ID')}</span></div>
           <div class="row" style="color:#10B981;font-weight:700">
               <span>Kembalian</span><span>Rp${tx.cash_change.toLocaleString('id-ID')}</span>
           </div>` : '';

    const catatanBlock = catatan
        ? `<div class="catatan"><span>📝 Catatan:</span> ${catatan}</div>` : '';

    const w = window.open('', '_blank', 'width=480,height=680');
    w.document.write(`<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<title>Struk TXN-${String(tx.id).padStart(3,'0')}</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'Courier New',monospace;font-size:13px;background:#fff;padding:20px;max-width:320px;margin:0 auto;}
  .actions{display:flex;gap:10px;margin-bottom:18px;}
  .btn{flex:1;padding:10px;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;}
  .btn-print{background:#1A56DB;color:#fff;}
  .btn-pdf{background:#10B981;color:#fff;}
  .center{text-align:center;}
  .logo{height:36px;margin-bottom:6px;object-fit:contain;}
  .biz{font-size:16px;font-weight:800;margin-bottom:2px;}
  .sub{font-size:11px;color:#475569;margin-bottom:10px;}
  hr{border:none;border-top:1px dashed #CBD5E1;margin:10px 0;}
  .row{display:flex;justify-content:space-between;margin-bottom:5px;font-size:13px;}
  .row.total{font-weight:800;font-size:15px;margin-top:4px;}
  .row.disc{color:#10B981;}
  .footer{text-align:center;font-size:11px;color:#64748B;margin-top:4px;}
  .catatan{font-size:11px;color:#78350F;background:#FFFBEB;border:1px dashed #FDE68A;border-radius:4px;padding:6px 8px;margin-top:8px;font-style:italic;}
  .thanks{text-align:center;font-size:14px;font-weight:800;color:#10B981;margin-top:14px;padding-top:10px;border-top:1px dashed #CBD5E1;}
  @media print{.actions{display:none!important;}body{padding:0;}@page{margin:6mm;size:80mm auto;}}
</style></head><body>
<div class="actions">
  <button class="btn btn-print" onclick="window.print()">🖨️ Cetak</button>
  <button class="btn btn-pdf" onclick="window.print()">📄 Simpan PDF</button>
</div>
<div class="center">
  <img class="logo" src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png" onerror="this.style.display='none'">
  <div class="biz">${tx.biz}</div>
  <div class="sub">${tx.date} • TXN-${String(tx.id).padStart(3,'0')}</div>
</div>
<hr>
${itemRows}
<hr>
${discRow}
<div class="row total"><span>TOTAL</span><span>Rp${tx.total.toLocaleString('id-ID')}</span></div>
${changeRows}
<hr>
<div class="footer">Pelanggan: ${tx.customer}${tx.phone ? ' • ' + tx.phone : ''}</div>
<div class="footer">Kasir: ${tx.kasir} • Status: ${tx.status}</div>
${catatanBlock}
<div class="thanks">🙏 Terima kasih sudah berbelanja!</div>
</body></html>`);
    w.document.close();
}

// Close on overlay click
document.getElementById('modal-tx-detail').addEventListener('click', function(e) {
    if (e.target === this) closeTxModal();
});
</script>

@endsection