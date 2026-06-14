<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DejaVu Sans',sans-serif;font-size:11px;color:#0f172a;background:#fff;padding:10px;width:72mm;}
.header{text-align:center;margin-bottom:10px;padding-bottom:8px;border-bottom:1px dashed #cbd5e1;}
.biz-name{font-size:14px;font-weight:700;margin-bottom:3px;}
.tx-info{font-size:10px;color:#64748b;}
.section-label{font-size:9px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin:8px 0 4px;}
.item-row{display:flex;justify-content:space-between;margin-bottom:3px;font-size:11px;}
.item-left{flex:1;}
.item-right{font-weight:700;text-align:right;min-width:60px;}
.divider{border:none;border-top:1px dashed #cbd5e1;margin:8px 0;}
.disc-row{display:flex;justify-content:space-between;font-size:11px;color:#10b981;font-weight:600;margin-bottom:3px;}
.total-row{display:flex;justify-content:space-between;font-size:13px;font-weight:700;margin-top:4px;padding:6px 8px;background:#f0f7ff;border-radius:4px;}
.total-label{color:#1e40af;}
.total-amount{color:#1A56DB;}
.meta{margin-top:8px;padding-top:6px;border-top:1px dashed #cbd5e1;font-size:10px;color:#64748b;}
.meta-row{display:flex;gap:4px;margin-bottom:2px;}
.meta-key{min-width:60px;color:#94a3b8;}
.meta-val{font-weight:600;color:#0f172a;}
.catatan{margin-top:6px;padding:5px 7px;background:#fffbeb;border:1px solid #fde68a;border-radius:4px;}
.catatan-label{font-size:9px;font-weight:700;color:#92400e;text-transform:uppercase;margin-bottom:2px;}
.catatan-text{font-size:10px;color:#78350f;}
.footer{text-align:center;margin-top:10px;padding-top:6px;border-top:1px dashed #cbd5e1;font-size:10px;color:#94a3b8;}
.status-ok{color:#10b981;font-weight:700;}
</style>
</head>
<body>
@php
    $raw   = $transaction->getRawOriginal('items');
    $items = is_string($raw) ? json_decode($raw, true) ?? [] : ($transaction->items ?? []);
@endphp

<div class="header">
    <div class="biz-name">{{ $transaction->business->name }}</div>
    <div class="tx-info">
        {{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB<br>
        TXN-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}
        &bull; Kasir: {{ $transaction->kasir?->name ?? '-' }}
    </div>
</div>

<div class="section-label">Detail Pesanan</div>

@foreach($items as $item)
<div class="item-row">
    <div class="item-left">{{ $item['name'] }} ×{{ $item['qty'] }}</div>
    <div class="item-right">Rp{{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 0, ',', '.') }}</div>
</div>
@endforeach

<hr class="divider">

@if(($transaction->discount ?? 0) > 0)
<div class="disc-row">
    <span>🏷️ Diskon</span>
    <span>-Rp{{ number_format($transaction->discount, 0, ',', '.') }}</span>
</div>
@endif

<div class="total-row">
    <span class="total-label">TOTAL</span>
    <span class="total-amount">Rp{{ number_format($transaction->total, 0, ',', '.') }}</span>
</div>

<div class="meta">
    <div class="meta-row">
        <span class="meta-key">Pelanggan</span>
        <span class="meta-val">{{ $transaction->customer?->name ?? 'Pelanggan' }}</span>
    </div>
    @if($transaction->customer?->phone)
    <div class="meta-row">
        <span class="meta-key">No. HP</span>
        <span class="meta-val">{{ $transaction->customer->phone }}</span>
    </div>
    @endif
    <div class="meta-row">
        <span class="meta-key">Status</span>
        <span class="meta-val status-ok">✅ {{ $transaction->status }}</span>
    </div>
</div>

@if($transaction->catatan)
<div class="catatan">
    <div class="catatan-label">📝 Catatan</div>
    <div class="catatan-text">{{ $transaction->catatan }}</div>
</div>
@endif

<div class="footer">
    Terima kasih sudah berbelanja! 🙏<br>
    <strong>{{ $transaction->business->name }}</strong><br>
    Struk digital oleh JagaBisnis POS
</div>
</body>
</html>