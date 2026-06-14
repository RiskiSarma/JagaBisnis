@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('content')

{{-- Page Header --}}
<div class="flex items-start justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Riwayat Transaksi</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            Hari ini:
            <strong class="text-blue-600 dark:text-blue-400 font-mono">Rp {{ number_format($todayTotal, 0, ',', '.') }}</strong>
            &bull;
            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ $todayLunas }} lunas</span>
            @if($todayBelum > 0)
                &bull;
                <span class="text-red-500 dark:text-red-400 font-semibold">{{ $todayBelum }} belum lunas</span>
            @endif
        </p>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 mb-4">
    <a href="{{ request()->fullUrlWithQuery(['filter' => 'today']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
           {{ $filter === 'today'
               ? 'bg-blue-600 text-white shadow-sm'
               : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-blue-400 hover:text-blue-600' }}">
        Hari Ini
    </a>
    <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
           {{ $filter === 'all'
               ? 'bg-blue-600 text-white shadow-sm'
               : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-blue-400 hover:text-blue-600' }}">
        Semua Transaksi
    </a>
</div>

{{-- Transactions Table --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" style="min-width:600px">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-[0.05em] text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">No. HP</th>
                    <th class="px-4 py-3">Items</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($transactions as $tx)
                @php
                    $raw = $tx->getRawOriginal('items');
                    $itemsParsed = is_string($raw) ? json_decode($raw, true) ?? [] : ($tx->items ?? []);
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-4 py-3">
                        <code class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded">
                            TXN-{{ str_pad($tx->id, 3, '0', STR_PAD_LEFT) }}
                        </code>
                    </td>
                    <td class="px-4 py-3 text-slate-400 dark:text-slate-500 whitespace-nowrap text-xs">
                        {{ $tx->created_at->format('d/m/y') }}
                        <span class="block">{{ $tx->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">
                        {{ $tx->customer?->name ?? 'Pelanggan' }}
                    </td>
                    <td class="px-4 py-3 text-xs">
                        @if($tx->customer?->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', preg_replace('/^0/', '62', $tx->customer->phone)) }}"
                               target="_blank"
                               class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                {{ $tx->customer->phone }}
                            </a>
                        @else
                            <span class="text-slate-400 dark:text-slate-500">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                        {{ count($itemsParsed) }} item
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-bold text-slate-900 dark:text-white font-mono">
                            Rp {{ number_format($tx->total, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('kasir.history.toggle-status', $tx) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold cursor-pointer transition-colors
                                    {{ $tx->status === 'lunas'
                                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-400'
                                        : 'bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-400' }}">
                                @if($tx->status === 'lunas')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Lunas
                                @else
                                    ! Belum
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        {{-- Struk → buka modal --}}
                        <button type="button"
                            onclick="bukaModalStruk({{ $tx->id }})"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-blue-500 hover:text-blue-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Struk
                        </button>

                        {{-- WA → langsung buka tab struk digital (blob) --}}
                        @if($tx->customer?->phone)
                        <button type="button"
                            onclick="bukaStrukWATab({{ $tx->id }})"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg bg-[#25D366] text-white hover:bg-[#1fba59] transition-colors ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-16 text-center">
                        <div class="inline-flex flex-col items-center gap-3 text-slate-400 dark:text-slate-500">
                            <div class="p-4 bg-slate-100 dark:bg-slate-700/50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/></svg>
                            </div>
                            <p class="text-sm">{{ $filter === 'today' ? 'Belum ada transaksi hari ini.' : 'Belum ada transaksi.' }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL STRUK — inline, semua data di JS
     ══════════════════════════════════════════ --}}
<div id="modalStrukOverlay"
     class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this)tutupModalStruk()">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <!-- Modal header -->
        <div style="padding:16px 18px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #E2E8F0">
            <span id="modalStrukTitle" style="font-size:17px;font-weight:800;color:#0f172a"></span>
            <button onclick="tutupModalStruk()"
                style="background:none;border:none;cursor:pointer;color:#94A3B8;font-size:20px;line-height:1;padding:2px 6px">×</button>
        </div>
        <div id="modalStrukContent"></div>
    </div>
</div>

<script>
const STRUK_DATA = {
@foreach($transactions as $tx)
@php
    $raw2    = $tx->getRawOriginal('items');
    $itms    = is_string($raw2) ? json_decode($raw2, true) ?? [] : ($tx->items ?? []);
    $bizName  = $tx->business?->name ?? config('app.name');
    $kasirName = $tx->kasir?->name ?? auth()->user()->name;
@endphp
{{ $tx->id }}: {
    id:        {{ $tx->id }},
    code:      "TXN-{{ str_pad($tx->id, 3, '0', STR_PAD_LEFT) }}",
    tanggal:   "{{ $tx->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d') }}",
    waktu:     "{{ $tx->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}",
    bisnis:    @json($bizName),
    kasir:     @json($kasirName),
    pelanggan: @json($tx->customer?->name ?? 'Pelanggan'),
    phone:     @json($tx->customer?->phone ?? ''),
    total:     {{ $tx->total }},
    discount:  {{ $tx->discount ?? 0 }},
    status:    @json($tx->status),
    catatan:   @json($tx->catatan ?? ''),
    items: [
        @foreach($itms as $item)
        { name: @json($item['name'] ?? ''), qty: {{ $item['qty'] ?? 1 }}, price: {{ $item['price'] ?? 0 }} },
        @endforeach
    ],
    urlCetak: "{{ route('kasir.receipt', $tx) }}",
    urlPdf:   "{{ route('kasir.receipt.pdf', $tx) }}",
    hasPhone: {{ $tx->customer?->phone ? 'true' : 'false' }},
},
@endforeach
};

// ── Helper buat halaman struk HTML (blob) — persis referensi ──────
function buatHtmlStruk(t, extra, catatan) {
    const itemRows = t.items.map(it => `
        <div class="receipt-row">
            <span>${it.name} &times;${it.qty}</span>
            <span>Rp${(it.price * it.qty).toLocaleString('id-ID')}</span>
        </div>`).join('');

    const discRow = t.discount > 0 ? `
        <div class="receipt-row" style="color:#10B981">
            <span>Diskon</span><span>-Rp${t.discount.toLocaleString('id-ID')}</span>
        </div>` : '';

    const catatanBlock = catatan ? `
        <div style="text-align:center;font-size:11px;color:#78350f;background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:8px 12px;margin-top:8px">
            📝 ${catatan}
        </div>` : '';

    const extraBlock = extra ? `
        <div style="text-align:center;background:#f0fdf4;border-radius:10px;padding:10px 16px;margin:10px 0 0;font-size:13px;color:#15803d;font-weight:600">
            💬 ${extra}
        </div>` : '';

    return `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Struk Belanja — ${t.bisnis}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#1A56DB 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;font-family:'Plus Jakarta Sans',sans-serif;}
.card{background:#fff;border-radius:24px;max-width:420px;width:100%;box-shadow:0 32px 80px rgba(0,0,0,.4);overflow:hidden;}
.card-header{background:linear-gradient(135deg,#1A56DB,#3B82F6);padding:28px 28px 22px;text-align:center;position:relative;}
.card-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:24px;background:#fff;border-radius:24px 24px 0 0;}
.logo{height:44px;object-fit:contain;margin-bottom:10px;filter:drop-shadow(0 2px 8px rgba(0,0,0,.2));}
.biz-name{color:#fff;font-size:20px;font-weight:800;margin-bottom:4px;}
.tx-info{color:rgba(255,255,255,.7);font-size:12px;}
.tx-badge{display:inline-block;background:rgba(255,255,255,.18);border-radius:20px;padding:3px 12px;font-size:11px;font-weight:700;color:#fff;margin-top:6px;letter-spacing:.4px;}
.card-body{padding:24px 28px;}
.section-label{font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;}
.item-row{display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px dashed #e2e8f0;}
.item-row:last-child{border-bottom:none;}
.item-name{flex:1;font-size:14px;font-weight:600;color:#0f172a;}
.item-qty{font-size:12px;color:#64748b;font-weight:500;min-width:30px;text-align:center;}
.item-price{font-family:'Space Grotesk',sans-serif;font-size:14px;font-weight:700;color:#1A56DB;min-width:90px;text-align:right;}
.divider{height:1px;background:repeating-linear-gradient(90deg,#e2e8f0 0,#e2e8f0 6px,transparent 6px,transparent 12px);margin:14px 0;}
.summary-row{display:flex;justify-content:space-between;font-size:13px;color:#475569;margin-bottom:6px;}
.summary-row.disc{color:#10b981;font-weight:600;}
.total-row{display:flex;justify-content:space-between;align-items:center;background:#f0f7ff;border-radius:12px;padding:14px 16px;margin:4px 0 16px;}
.total-label{font-size:13px;font-weight:700;color:#1e40af;}
.total-amount{font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:800;color:#1A56DB;}
.meta-box{background:#f8fafc;border-radius:12px;padding:14px 16px;margin-bottom:14px;}
.meta-row{display:flex;gap:8px;font-size:12px;color:#475569;margin-bottom:5px;}
.meta-row:last-child{margin-bottom:0;}
.meta-row .key{color:#94a3b8;min-width:60px;}
.meta-row .val{font-weight:600;color:#0f172a;}
.thankyou{text-align:center;padding:18px 0 4px;}
.thankyou-emoji{font-size:36px;margin-bottom:8px;}
.thankyou-text{font-size:16px;font-weight:800;color:#0f172a;margin-bottom:4px;}
.thankyou-sub{font-size:13px;color:#64748b;}
.footer{text-align:center;padding:16px 28px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:11px;color:#94a3b8;}
.footer strong{color:#475569;}
</style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <img class="logo" src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png" onerror="this.style.display='none'">
        <div class="biz-name">${t.bisnis}</div>
        <div class="tx-info">${t.tanggal} ${t.waktu} WIB</div>
        <div class="tx-badge">🔖 ${t.code}</div>
    </div>
    <div class="card-body">
        <div class="section-label">Detail Pesanan</div>
        ${t.items.map(it => `
        <div class="item-row">
            <div class="item-name">${it.name}</div>
            <div class="item-qty">× ${it.qty}</div>
            <div class="item-price">Rp${(it.price*it.qty).toLocaleString('id-ID')}</div>
        </div>`).join('')}
        <div class="divider"></div>
        ${t.discount > 0 ? `<div class="summary-row disc"><span>🏷️ Diskon</span><span>-Rp${t.discount.toLocaleString('id-ID')}</span></div>` : ''}
        <div class="total-row">
            <div class="total-label">TOTAL PEMBAYARAN</div>
            <div class="total-amount">Rp${t.total.toLocaleString('id-ID')}</div>
        </div>
        <div class="meta-box">
            <div class="meta-row"><span class="key">Pelanggan</span><span class="val">${t.pelanggan}</span></div>
            ${t.phone ? `<div class="meta-row"><span class="key">No. HP</span><span class="val">${t.phone}</span></div>` : ''}
            <div class="meta-row"><span class="key">Kasir</span><span class="val">${t.kasir}</span></div>
            <div class="meta-row"><span class="key">Tanggal</span><span class="val">${t.tanggal} ${t.waktu}</span></div>
            <div class="meta-row"><span class="key">Status</span><span class="val" style="color:#10b981">✅ ${t.status}</span></div>
        </div>
        ${catatan ? `<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;margin-bottom:14px"><div style="font-size:11px;font-weight:800;color:#92400e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">📝 Catatan</div><div style="font-size:13px;color:#78350f">${catatan}</div></div>` : ''}
        <div class="thankyou">
            <div class="thankyou-emoji">🙏</div>
            <div class="thankyou-text">Terima kasih sudah berbelanja!</div>
            <div class="thankyou-sub">Sampai jumpa kembali di <strong>${t.bisnis}</strong></div>
        </div>
        ${extraBlock}
    </div>
    <div class="footer">Struk digital oleh <strong>JagaBisnis POS</strong> &bull; Dibuat ${t.tanggal}</div>
</div>
</body>
</html>`;
}

// ── Tombol WA di TABEL → buka tab struk digital (blob) ───────────
function bukaStrukWATab(txId) {
    const t = STRUK_DATA[txId];
    if (!t) return;
    const html = buatHtmlStruk(t, '', t.catatan);
    const blob = new Blob([html], { type: 'text/html' });
    window.open(URL.createObjectURL(blob), '_blank');
}

// ── Tombol WA di MODAL → langsung buka WhatsApp ──────────────────
function kirimStrukWA(txId) {
    const t       = STRUK_DATA[txId];
    if (!t || !t.hasPhone) return;
    const extra   = (document.getElementById('waExtra_' + txId) || {}).value || '';
    const catatan = (document.getElementById('catatan_'  + txId) || {}).value || '';

    // Buka halaman struk di tab baru
    const html = buatHtmlStruk(t, extra, catatan);
    const blob = new Blob([html], { type: 'text/html' });
    window.open(URL.createObjectURL(blob), '_blank');

    // Bangun pesan WA
    const itemLines = t.items
        .map(i => `  • ${i.name} ×${i.qty}  =  Rp${(i.price * i.qty).toLocaleString('id-ID')}`)
        .join('\n');
    const discLine    = t.discount > 0 ? `\n🏷️ Diskon: -Rp${t.discount.toLocaleString('id-ID')}` : '';
    const catatanLine = catatan ? `\n📝 Catatan: ${catatan}` : '';

    const msg =
`🧾 *NOTA BELANJA DIGITAL*
━━━━━━━━━━━━━━━━━━
🏪 *${t.bisnis}*
📅 ${t.tanggal}  ${t.waktu} WIB
🔖 No. Transaksi: ${t.code}
━━━━━━━━━━━━━━━━━━
${itemLines}
━━━━━━━━━━━━━━━━━━${discLine}
💰 *TOTAL : Rp${t.total.toLocaleString('id-ID')}*
━━━━━━━━━━━━━━━━━━
👤 ${t.pelanggan}${t.phone ? '\n📱 ' + t.phone : ''}
👩‍💼 Kasir: ${t.kasir}
✅ Status: ${t.status}${catatanLine}
━━━━━━━━━━━━━━━━━━
${extra ? '💬 ' + extra + '\n\n' : ''}🙏 *Terima kasih sudah berbelanja di ${t.bisnis}!*`;

    let phone = t.phone.replace(/[\s\-().]/g, '');
    if (phone.startsWith('0')) phone = '62' + phone.slice(1);
    if (phone.startsWith('+')) phone = phone.slice(1);

    setTimeout(() => {
        window.open(`https://wa.me/${phone}?text=${encodeURIComponent(msg)}`, '_blank');
    }, 600);

    tutupModalStruk();
}

// ── Buka modal struk — persis tampilan referensi ──────────────────
function bukaModalStruk(txId) {
    var t = STRUK_DATA[txId];
    if (!t) return;

    var itemHtml = '';
    for (var i = 0; i < t.items.length; i++) {
        var it = t.items[i];
        var borderStyle = (i < t.items.length - 1) ? 'border-bottom:1px dashed #e2e8f0' : '';
        itemHtml += '<div style="display:flex;align-items:center;padding:8px 0;' + borderStyle + '">'
            + '<div style="flex:1;font-size:13px;font-weight:600;color:#0f172a">' + it.name + ' &times;' + it.qty + '</div>'
            + '<div style="font-size:13px;font-weight:700;color:#475569;white-space:nowrap">Rp' + (it.price * it.qty).toLocaleString('id-ID') + '</div>'
            + '</div>';
    }

    var discHtml = '';
    if (t.discount > 0) {
        discHtml = '<div style="display:flex;justify-content:space-between;font-size:12px;color:#10B981;font-weight:600;margin-bottom:4px">'
            + '<span>Diskon</span><span>-Rp' + t.discount.toLocaleString('id-ID') + '</span>'
            + '</div>';
    }

    var phoneHtml = t.phone ? '<div style="font-size:11px;color:#94A3B8">HP: ' + t.phone + '</div>' : '';
    var catatanHtml = t.catatan ? '<div style="text-align:center;font-size:11px;color:#64748B;font-style:italic;margin-top:3px">Catatan: ' + t.catatan + '</div>' : '';

    var waHtml = '';
    if (t.hasPhone) {
        waHtml = '<div style="margin-bottom:10px">'
            + '<input type="text" id="waExtra_' + txId + '" placeholder="Ucapan / pesan tambahan ke pelanggan (opsional)..." style="width:100%;padding:9px 12px;border:1px solid #E2E8F0;border-radius:8px;font-family:inherit;font-size:13px;outline:none;color:#0f172a;background:#fff;margin-bottom:8px;box-sizing:border-box">'
            + '<button onclick="kirimStrukWA(' + txId + ')" style="width:100%;padding:13px;border:none;border-radius:8px;background:#25D366;color:#fff;font-family:inherit;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">'
            + '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>'
            + 'Kirim Link Struk via WA'
            + '</button>'
            + '</div>';
    } else {
        waHtml = '<div style="padding:10px 12px;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;font-size:12px;color:#94A3B8;margin-bottom:10px">'
            + 'Nota WA tidak tersedia - pelanggan tidak memiliki nomor HP.'
            + '</div>';
    }

    var html = '<div style="padding:18px">'

        + '<div style="font-family:monospace;border:1px dashed #E2E8F0;border-radius:8px;padding:18px;background:#F8FAFC;margin-bottom:14px">'

            + '<div style="text-align:center;margin-bottom:14px">'
            + '<img src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png" style="height:30px;margin-bottom:6px;object-fit:contain;display:block;margin-left:auto;margin-right:auto" onerror="this.style.display=\'none\'">'
            + '<div style="font-size:15px;font-weight:800;color:#0f172a">' + t.bisnis + '</div>'
            + '<div style="font-size:11px;color:#94A3B8;margin-top:2px">' + t.tanggal + ' ' + t.waktu + ' &bull; Kasir: ' + t.kasir + '</div>'
            + phoneHtml
            + '</div>'

            + '<hr style="border:none;border-top:1px dashed #E2E8F0;margin:10px 0">'
            + itemHtml
            + '<hr style="border:none;border-top:1px dashed #E2E8F0;margin:10px 0">'
            + discHtml

            + '<div style="display:flex;justify-content:space-between;font-size:15px;font-weight:800;margin-top:4px">'
            + '<span style="color:#0f172a">TOTAL</span>'
            + '<span style="color:#1A56DB">Rp' + t.total.toLocaleString('id-ID') + '</span>'
            + '</div>'

            + '<hr style="border:none;border-top:1px dashed #E2E8F0;margin:10px 0">'
            + '<div style="text-align:center;font-size:11px;color:#94A3B8">Pelanggan: ' + t.pelanggan + '</div>'
            + catatanHtml
            + '<div style="text-align:center;margin-top:6px;font-size:12px;color:#10B981;font-weight:600">Terima kasih sudah berbelanja!</div>'

        + '</div>'

        + '<div style="margin-bottom:14px">'
        + '<label style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px">📝 CATATAN</label>'
        + '<textarea id="catatan_' + txId + '" rows="2" placeholder="Tambah catatan transaksi..." style="width:100%;padding:9px 12px;border:1px solid #E2E8F0;border-radius:8px;font-family:inherit;font-size:13px;background:#fff;color:#0f172a;outline:none;resize:vertical;line-height:1.5;box-sizing:border-box">' + t.catatan + '</textarea>'
        + '</div>'

        + waHtml

        + '<div style="display:flex;justify-content:flex-end;gap:8px;padding-top:14px;border-top:1px solid #E2E8F0;flex-wrap:wrap">'
        + '<a href="' + t.urlCetak + '" target="_blank" style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border:1px solid #E2E8F0;border-radius:8px;font-size:13px;font-weight:600;color:#475569;text-decoration:none;background:#fff;cursor:pointer">'
        + '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>'
        + 'Cetak</a>'
        + '<a href="' + t.urlPdf + '" style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border:1px solid #E2E8F0;border-radius:8px;font-size:13px;font-weight:600;color:#475569;text-decoration:none;background:#fff;cursor:pointer">'
        + '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="12" y2="18"/><line x1="15" y1="15" x2="12" y2="18"/></svg>'
        + 'PDF</a>'
        + '<button onclick="tutupModalStruk()" style="display:inline-flex;align-items:center;padding:8px 14px;border:1px solid #E2E8F0;border-radius:8px;font-size:13px;font-weight:600;color:#475569;background:#fff;cursor:pointer">'
        + 'Tutup</button>'
        + '</div>'

    + '</div>';

    document.getElementById('modalStrukContent').innerHTML = html;
    document.getElementById('modalStrukTitle').textContent = 'Detail Transaksi ' + t.code;
    document.getElementById('modalStrukOverlay').classList.remove('hidden');
}

function tutupModalStruk() {
    document.getElementById('modalStrukOverlay').classList.add('hidden');
    document.getElementById('modalStrukContent').innerHTML = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') tutupModalStruk();
});
</script>

@endsection