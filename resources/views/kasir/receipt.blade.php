<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Struk TXN-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }} — {{ $transaction->business->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#1A56DB 100%);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding:20px;font-family:'Plus Jakarta Sans',sans-serif;}
.no-print{margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;justify-content:center;}
.btn{padding:10px 20px;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none;}
.btn-print{background:#1A56DB;color:#fff;}
.btn-pdf{background:#10B981;color:#fff;}
.btn-back{background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);}
.card{background:#fff;border-radius:24px;max-width:420px;width:100%;box-shadow:0 32px 80px rgba(0,0,0,.4);overflow:hidden;}
.card-header{background:linear-gradient(135deg,#1A56DB,#3B82F6);padding:28px 28px 22px;text-align:center;position:relative;}
.card-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:24px;background:#fff;border-radius:24px 24px 0 0;}
.logo{height:44px;object-fit:contain;margin-bottom:10px;filter:drop-shadow(0 2px 8px rgba(0,0,0,.2));display:block;margin-left:auto;margin-right:auto;}
.biz-name{color:#fff;font-size:20px;font-weight:800;margin-bottom:4px;}
.tx-info{color:rgba(255,255,255,.75);font-size:12px;}
.tx-badge{display:inline-block;background:rgba(255,255,255,.18);border-radius:20px;padding:3px 12px;font-size:11px;font-weight:700;color:#fff;margin-top:6px;letter-spacing:.4px;}
.card-body{padding:24px 28px;}
.section-label{font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;}
.item-row{display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px dashed #e2e8f0;}
.item-row:last-child{border-bottom:none;}
.item-name{flex:1;font-size:14px;font-weight:600;color:#0f172a;}
.item-qty{font-size:12px;color:#64748b;min-width:30px;text-align:center;}
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
.meta-row .key{color:#94a3b8;min-width:70px;}
.meta-row .val{font-weight:600;color:#0f172a;}
.catatan-box{background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;margin-bottom:14px;}
.catatan-label{font-size:11px;font-weight:800;color:#92400e;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;}
.catatan-text{font-size:13px;color:#78350f;line-height:1.5;}
.thankyou{text-align:center;padding:18px 0 4px;}
.thankyou-emoji{font-size:36px;margin-bottom:8px;}
.thankyou-text{font-size:16px;font-weight:800;color:#0f172a;margin-bottom:4px;}
.thankyou-sub{font-size:13px;color:#64748b;}
.footer{text-align:center;padding:16px 28px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:11px;color:#94a3b8;}
.footer strong{color:#475569;}
@media print{
    body{background:#fff;padding:0;}
    .no-print{display:none!important;}
    .card{box-shadow:none;border-radius:0;max-width:100%;}
    @page{margin:6mm;size:80mm auto;}
}
</style>
</head>
<body>

<div class="no-print">
    <button class="btn btn-print" onclick="window.print()">🖨️ Cetak</button>
    <a href="{{ route('kasir.receipt.pdf', $transaction) }}" class="btn btn-pdf">📄 Download PDF</a>
    <button class="btn btn-back" onclick="window.close()">✕ Tutup</button>
</div>

@php
    $raw   = $transaction->getRawOriginal('items');
    $items = is_string($raw) ? json_decode($raw, true) ?? [] : ($transaction->items ?? []);
@endphp

<div class="card">
    <div class="card-header">
        <img class="logo"
             src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png"
             alt="Logo" onerror="this.style.display='none'">
        <div class="biz-name">{{ $transaction->business->name }}</div>
        <div class="tx-info">
            {{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
            &bull; Kasir: {{ $transaction->kasir?->name ?? '-' }}
        </div>
        <div class="tx-badge">🔖 TXN-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="card-body">
        <div class="section-label">Detail Pesanan</div>

        @foreach($items as $item)
        <div class="item-row">
            <div class="item-name">{{ $item['name'] }}</div>
            <div class="item-qty">× {{ $item['qty'] }}</div>
            <div class="item-price">Rp{{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 0, ',', '.') }}</div>
        </div>
        @endforeach

        <div class="divider"></div>

        @if(($transaction->discount ?? 0) > 0)
        <div class="summary-row disc">
            <span>🏷️ Diskon</span>
            <span>-Rp{{ number_format($transaction->discount, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="total-row">
            <div class="total-label">TOTAL PEMBAYARAN</div>
            <div class="total-amount">Rp{{ number_format($transaction->total, 0, ',', '.') }}</div>
        </div>

        <div class="meta-box">
            <div class="meta-row">
                <span class="key">Pelanggan</span>
                <span class="val">{{ $transaction->customer?->name ?? 'Pelanggan' }}</span>
            </div>
            @if($transaction->customer?->phone)
            <div class="meta-row">
                <span class="key">No. HP</span>
                <span class="val">{{ $transaction->customer->phone }}</span>
            </div>
            @endif
            <div class="meta-row">
                <span class="key">Kasir</span>
                <span class="val">{{ $transaction->kasir?->name ?? '-' }}</span>
            </div>
            <div class="meta-row">
                <span class="key">Tanggal</span>
                <span class="val">{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</span>
            </div>
            <div class="meta-row">
                <span class="key">Status</span>
                <span class="val" style="color:#10b981">✅ {{ $transaction->status }}</span>
            </div>
        </div>

        @if($transaction->catatan)
        <div class="catatan-box">
            <div class="catatan-label">📝 Catatan</div>
            <div class="catatan-text">{{ $transaction->catatan }}</div>
        </div>
        @endif

        <div class="thankyou">
            <div class="thankyou-emoji">🙏</div>
            <div class="thankyou-text">Terima kasih sudah berbelanja!</div>
            <div class="thankyou-sub">Sampai jumpa kembali di <strong>{{ $transaction->business->name }}</strong></div>
        </div>
    </div>

    <div class="footer">
        Struk digital oleh <strong>JagaBisnis POS</strong>
        &bull; Dibuat {{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y') }}
    </div>
</div>

</body>
</html>