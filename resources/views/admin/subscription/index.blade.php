{{-- FILE: resources/views/admin/subscription/index.blade.php --}}
{{-- PERUBAHAN: Check business.status SEBELUM check subscription_status --}}

@extends('layouts.app')

@section('title', 'Subscription')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- Status Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-7">
            <div class="flex items-start gap-4">

                {{-- ── PRIORITAS: Dinonaktifkan oleh Platform ── --}}
                @if($business->status !== 'active')
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0">
                        <i class="bi bi-slash-circle text-slate-500 dark:text-slate-400 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-slate-800 dark:text-white text-base mb-1">Status Akun Bisnis</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Akses bisnis Anda sedang <span class="font-semibold text-red-600 dark:text-red-400">dinonaktifkan sementara</span> oleh platform.
                            Hubungi <span class="font-semibold">tim support</span> untuk informasi lebih lanjut.
                        </p>
                        @if($business->subscription_status === 'active' && $business->subscription_ends_at?->isFuture())
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">
                                Catatan: Langganan paket <span class="font-semibold uppercase">{{ $business->paket }}</span> Anda masih aktif hingga
                                {{ $business->subscription_ends_at->translatedFormat('d M Y') }}.
                            </p>
                        @endif
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">Nonaktif</span>

                {{-- ── Status normal (trial / active / expired) ── --}}
                @elseif($business->hasAccess())
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center shrink-0">
                        <i class="bi bi-shield-check text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-slate-800 dark:text-white text-base mb-1">Status Akun Bisnis</h2>
                        @if($business->subscription_status === 'trial')
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                Anda sedang dalam masa <span class="font-semibold text-brand">uji coba gratis</span>,
                                sisa <span class="font-semibold">{{ $business->daysRemaining() }} hari</span>
                                (berakhir {{ $business->trial_ends_at->translatedFormat('d M Y') }}).
                            </p>
                        @elseif($business->subscription_status === 'active')
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                Paket <span class="font-bold uppercase text-brand">{{ $business->paket }}</span> sedang aktif,
                                berlaku sampai <span class="font-semibold">{{ $business->subscription_ends_at->translatedFormat('d M Y') }}</span>
                                ({{ $business->daysRemaining() }} hari lagi).
                            </p>
                        @endif
                    </div>
                    @php
                        $statusBadge = match($business->subscription_status) {
                            'trial'  => ['bg-blue-100 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300', 'Trial'],
                            'active' => ['bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300', 'Aktif'],
                            default  => ['bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300', '-'],
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0 {{ $statusBadge[0] }}">{{ $statusBadge[1] }}</span>

                @else
                    <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-500/15 flex items-center justify-center shrink-0">
                        <i class="bi bi-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-slate-800 dark:text-white text-base mb-1">Status Akun Bisnis</h2>
                        <p class="text-sm text-red-600 dark:text-red-400 font-semibold">
                            {{ $business->subscription_status === 'trial'
                                ? 'Masa uji coba gratis Anda telah berakhir. Silakan pilih paket untuk melanjutkan akses.'
                                : 'Akses Anda berakhir. Silakan pilih dan bayar paket untuk mengaktifkan kembali.' }}
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0 bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300">Expired</span>
                @endif

            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 p-3.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium">
        <i class="bi bi-check-circle-fill text-base"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- JIKA BISNIS DINONAKTIFKAN, SEMBUNYIKAN FORM & PENDING --}}
    @if($business->status !== 'active')
        <div class="bg-red-50 dark:bg-red-900/15 border border-red-200 dark:border-red-800/60 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-500/15 flex items-center justify-center shrink-0">
                <i class="bi bi-info-circle text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-red-700 dark:text-red-300">
                    Akses Terbatas
                </p>
                <p class="text-xs text-red-600 dark:text-red-400/80 mt-1">
                    Fitur subscription tidak dapat diakses karena bisnis Anda sedang dinonaktifkan. 
                    Hubungi Super Admin untuk mengaktifkannya kembali.
                </p>
            </div>
        </div>
    @else

        {{-- Pending notice --}}
        @if($pendingSubscription)
        <div class="bg-amber-50 dark:bg-amber-900/15 border border-amber-200 dark:border-amber-800/60 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/15 flex items-center justify-center shrink-0">
                <i class="bi bi-hourglass-split text-amber-600 dark:text-amber-400 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-amber-700 dark:text-amber-300">
                    Pengajuan paket <span class="uppercase">{{ $pendingSubscription->paket }}</span> sedang menunggu konfirmasi
                </p>
                <p class="text-xs text-amber-600 dark:text-amber-400/80 mt-1">
                    Diajukan {{ $pendingSubscription->created_at->diffForHumans() }} · Mohon tunggu 1-24 jam.
                </p>
            </div>
        </div>
        @else

        {{-- Form pilih paket --}}
        <form id="subscriptionForm"
          class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-7 space-y-6">
            @csrf

            @if($errors->any())
                <div class="flex items-center gap-2 p-3.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
                    <i class="bi bi-exclamation-circle-fill text-base"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div id="alertBox" class="hidden flex items-center gap-2 p-3.5 rounded-xl text-sm font-medium"></div>

            <div>
                <h2 class="font-bold text-slate-800 dark:text-white text-base mb-1.5">Pilih Paket</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Upgrade kapan saja, tanpa kontrak jangka panjang.</p>
            </div>

            {{-- Grid paket --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="paketGrid">

                <label class="relative cursor-pointer group">
                    <input type="radio" name="paket" value="starter" class="peer sr-only" onchange="onPaketChange('starter')" {{ old('paket') === 'starter' ? 'checked' : '' }}>
                    <div class="h-full border-2 border-slate-200 dark:border-slate-700 peer-checked:border-brand peer-checked:bg-brand/5 dark:peer-checked:bg-brand/10 rounded-xl p-5 transition-all hover:border-brand/40">
                        <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-4">
                            <i class="bi bi-box text-slate-500 dark:text-slate-300"></i>
                        </div>
                        <p class="font-bold text-slate-800 dark:text-white text-sm mb-1">Starter</p>
                        <p class="text-brand font-bold text-lg mb-1.5">Gratis</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">1 kasir · 100 produk</p>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <span class="absolute -top-2.5 right-3 bg-amber-400 text-amber-950 text-[10px] font-extrabold px-2 py-0.5 rounded-full z-10">⭐ POPULER</span>
                    <input type="radio" name="paket" value="pro" class="peer sr-only" onchange="onPaketChange('pro')" {{ old('paket') === 'pro' || !old('paket') ? 'checked' : '' }}>
                    <div class="h-full border-2 border-slate-200 dark:border-slate-700 peer-checked:border-brand peer-checked:bg-brand/5 dark:peer-checked:bg-brand/10 rounded-xl p-5 transition-all hover:border-brand/40">
                        <div class="w-10 h-10 rounded-lg bg-brand/10 flex items-center justify-center mb-4">
                            <i class="bi bi-rocket-takeoff text-brand"></i>
                        </div>
                        <p class="font-bold text-slate-800 dark:text-white text-sm mb-1">Pro</p>
                        <p class="text-brand font-bold text-lg mb-1.5">Rp 299.000<span class="text-xs font-normal text-slate-400">/bln</span></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">5 kasir · unlimited produk</p>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <input type="radio" name="paket" value="business" class="peer sr-only" onchange="onPaketChange('business')" {{ old('paket') === 'business' ? 'checked' : '' }}>
                    <div class="h-full border-2 border-slate-200 dark:border-slate-700 peer-checked:border-brand peer-checked:bg-brand/5 dark:peer-checked:bg-brand/10 rounded-xl p-5 transition-all hover:border-brand/40">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/15 flex items-center justify-center mb-4">
                            <i class="bi bi-buildings text-purple-500"></i>
                        </div>
                        <p class="font-bold text-slate-800 dark:text-white text-sm mb-1">Business</p>
                        <p class="text-brand font-bold text-lg mb-1.5">Rp 799.000<span class="text-xs font-normal text-slate-400">/bln</span></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Unlimited kasir · 5 bisnis</p>
                    </div>
                </label>
            </div>

            {{-- Payment section (untuk Pro/Business) --}}
            <div id="paymentSection" class="space-y-5 hidden border-t border-slate-100 dark:border-slate-700 pt-6">

                {{-- Tab pilihan metode --}}
                <div class="flex gap-2">
                    <button type="button" id="tabMidtrans" onclick="switchTab('midtrans')"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold border-2 transition-all border-brand bg-brand/5 text-brand">
                        <i class="bi bi-qr-code-scan mr-1"></i> Bayar Otomatis (QRIS/VA)
                    </button>
                    <button type="button" id="tabManual" onclick="switchTab('manual')"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold border-2 transition-all border-slate-200 dark:border-slate-600 text-slate-500 dark:text-slate-400">
                        <i class="bi bi-upload mr-1"></i> Transfer Manual
                    </button>
                </div>

                {{-- Tab Midtrans --}}
                <div id="panelMidtrans" class="space-y-3">
                    <div class="bg-blue-50 dark:bg-blue-900/15 border border-blue-200 dark:border-blue-800/60 rounded-xl px-4 py-3.5 flex items-center gap-3">
                        <i class="bi bi-lightning-charge-fill text-blue-600 dark:text-blue-400 text-base"></i>
                        <p class="text-xs text-blue-700 dark:text-blue-300 font-medium">
                            Paket aktif otomatis dalam hitungan detik setelah pembayaran berhasil — tanpa menunggu konfirmasi admin.
                            Tersedia QRIS, Virtual Account (BCA/BNI/BRI/Permata), GoPay & ShopeePay.
                        </p>
                    </div>
                    <button type="button" id="btnPayMidtrans" onclick="payWithMidtrans()"
                        class="w-full py-3.5 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-sm transition-all shadow-lg shadow-brand/25 flex items-center justify-center gap-2">
                        <i class="bi bi-credit-card"></i>
                        <span id="btnPayLabel">Bayar Sekarang</span>
                    </button>
                </div>

                {{-- Tab Manual --}}
                <div id="panelManual" class="space-y-5 hidden">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-2.5">Metode Pembayaran</label>
                        <div class="relative">
                            <i class="bi bi-credit-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <select name="payment_method" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-brand/30 focus:border-brand outline-none transition appearance-none">
                                <option value="">— Pilih metode —</option>
                                <option value="Transfer BCA">Transfer BCA</option>
                                <option value="Transfer BRI">Transfer BRI</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide mb-2.5">Upload Bukti Transfer</label>
                        <label for="proofInput" class="flex items-center gap-3 border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-xl px-5 py-5 cursor-pointer hover:border-brand/50 transition group">
                            <div class="w-10 h-10 rounded-lg bg-brand/10 flex items-center justify-center shrink-0 group-hover:bg-brand/15">
                                <i class="bi bi-cloud-upload text-brand text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200" id="proofFileName">Klik untuk pilih gambar</p>
                                <p class="text-xs text-slate-400 mt-1">Format JPG/PNG, maks 2MB.</p>
                            </div>
                            <input type="file" id="proofInput" name="proof" accept="image/*" class="hidden" onchange="document.getElementById('proofFileName').textContent = this.files[0]?.name || 'Klik untuk pilih gambar'">
                        </label>
                    </div>

                    <button type="button" onclick="submitManual()"
                        class="w-full py-3.5 rounded-xl bg-slate-700 hover:bg-slate-800 text-white font-bold text-sm transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-send"></i> Kirim Bukti Transfer
                    </button>
                </div>
            </div>

            {{-- Submit untuk Starter --}}
            <div id="starterSection" class="hidden">
                <div class="bg-emerald-50 dark:bg-emerald-900/15 border border-emerald-200 dark:border-emerald-800/60 rounded-xl px-4 py-3.5 flex items-center gap-3 mb-4">
                    <i class="bi bi-gift text-emerald-600 dark:text-emerald-400 text-base"></i>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 font-medium">Paket Starter gratis selamanya — aktifkan langsung tanpa pembayaran.</p>
                </div>
                <button type="button" onclick="submitFree()"
                    class="w-full py-3.5 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-sm transition-all shadow-lg shadow-brand/25 flex items-center justify-center gap-2">
                    <i class="bi bi-check-circle"></i> Aktifkan Paket Starter
                </button>
            </div>
        </form>
        @endif

    @endif

    {{-- History (tetap tampil meski bisnis nonaktif, for transparency) --}}
    @if($history->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm">Riwayat Pengajuan</h3>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-700">
            @foreach($history as $item)
            <div class="px-5 py-3.5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    @php
                        $iconMap = match($item->status) {
                            'approved' => ['bg-emerald-100 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400', 'bi-check-lg'],
                            'rejected' => ['bg-red-100 dark:bg-red-500/15 text-red-600 dark:text-red-400', 'bi-x-lg'],
                            default    => ['bg-amber-100 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400', 'bi-hourglass-split'],
                        };
                    @endphp
                    <div class="w-9 h-9 rounded-lg {{ $iconMap[0] }} flex items-center justify-center shrink-0">
                        <i class="bi {{ $iconMap[1] }} text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700 dark:text-white uppercase">{{ $item->paket }}</p>
                        <p class="text-xs text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full shrink-0
                    {{ $item->status === 'approved' ? 'bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300' : '' }}
                    {{ $item->status === 'rejected' ? 'bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300' : '' }}
                    {{ $item->status === 'pending' ? 'bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300' : '' }}">
                    {{ ucfirst($item->status) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function togglePaymentFields(paket) {
    document.getElementById('paymentFields').classList.toggle('hidden', paket === 'starter');
}
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="paket"]:checked');
    togglePaymentFields(checked ? checked.value : 'pro');
});
</script>
@if($midtransClientKey)
<script type="text/javascript"
    src="{{ $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ $midtransClientKey }}"></script>
@endif
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function onPaketChange(paket) {
    document.getElementById('paymentSection').classList.toggle('hidden', paket === 'starter');
    document.getElementById('starterSection').classList.toggle('hidden', paket !== 'starter');

    if (paket === 'pro' || paket === 'business') {
        const price = paket === 'pro' ? 'Rp 299.000' : 'Rp 799.000';
        document.getElementById('btnPayLabel').textContent = 'Bayar ' + price;
    }
}

function switchTab(tab) {
    const isMidtrans = tab === 'midtrans';
    document.getElementById('panelMidtrans').classList.toggle('hidden', !isMidtrans);
    document.getElementById('panelManual').classList.toggle('hidden', isMidtrans);

    document.getElementById('tabMidtrans').classList.toggle('border-brand', isMidtrans);
    document.getElementById('tabMidtrans').classList.toggle('bg-brand/5', isMidtrans);
    document.getElementById('tabMidtrans').classList.toggle('text-brand', isMidtrans);
    document.getElementById('tabMidtrans').classList.toggle('border-slate-200', !isMidtrans);
    document.getElementById('tabMidtrans').classList.toggle('dark:border-slate-600', !isMidtrans);
    document.getElementById('tabMidtrans').classList.toggle('text-slate-500', !isMidtrans);
    document.getElementById('tabMidtrans').classList.toggle('dark:text-slate-400', !isMidtrans);

    document.getElementById('tabManual').classList.toggle('border-brand', !isMidtrans);
    document.getElementById('tabManual').classList.toggle('bg-brand/5', !isMidtrans);
    document.getElementById('tabManual').classList.toggle('text-brand', !isMidtrans);
    document.getElementById('tabManual').classList.toggle('border-slate-200', isMidtrans);
    document.getElementById('tabManual').classList.toggle('dark:border-slate-600', isMidtrans);
    document.getElementById('tabManual').classList.toggle('text-slate-500', isMidtrans);
    document.getElementById('tabManual').classList.toggle('dark:text-slate-400', isMidtrans);
}

function showAlert(message, type = 'error') {
    const box = document.getElementById('alertBox');
    box.className = 'flex items-center gap-2 p-3.5 rounded-xl text-sm font-medium ' +
        (type === 'error'
            ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300'
            : 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300');
    box.innerHTML = '<i class="bi ' + (type === 'error' ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill') + ' text-base"></i>' + message;
    box.classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function payWithMidtrans() {
    const paket = document.querySelector('input[name="paket"]:checked')?.value;
    if (!paket || paket === 'starter') return showAlert('Pilih paket Pro atau Business.');

    const btn = document.getElementById('btnPayMidtrans');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

    fetch('{{ route("admin.subscription.snap-token") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ paket })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-credit-card"></i> <span>Bayar Sekarang</span>';

        if (!data.success) {
            return showAlert(data.message || 'Gagal memuat pembayaran.');
        }

        window.snap.pay(data.snap_token, {
            onSuccess: function() {
                showAlert('Pembayaran berhasil! Paket Anda sedang diaktifkan...', 'success');
                setTimeout(() => location.reload(), 2000);
            },
            onPending: function() {
                showAlert('Menunggu pembayaran Anda. Paket akan aktif otomatis setelah pembayaran terkonfirmasi.', 'success');
            },
            onError: function() {
                showAlert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                // user menutup popup tanpa menyelesaikan pembayaran
            }
        });
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-credit-card"></i> <span>Bayar Sekarang</span>';
        showAlert('Terjadi kesalahan jaringan.');
    });
}

function submitManual() {
    const paket = document.querySelector('input[name="paket"]:checked')?.value;
    const method = document.querySelector('select[name="payment_method"]').value;
    const proof = document.getElementById('proofInput').files[0];

    if (!paket || paket === 'starter') return showAlert('Pilih paket Pro atau Business.');
    if (!method) return showAlert('Pilih metode pembayaran.');
    if (!proof) return showAlert('Upload bukti transfer.');

    const formData = new FormData();
    formData.append('_token', CSRF);
    formData.append('paket', paket);
    formData.append('payment_method', method);
    formData.append('proof', proof);

    fetch('{{ route("admin.subscription.store-manual") }}', {
        method: 'POST',
        body: formData
    }).then(r => {
        if (r.redirected) {
            window.location.href = r.url;
        } else {
            location.reload();
        }
    });
}

function submitFree() {
    fetch('{{ route("admin.subscription.store-free") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF }
    }).then(r => {
        if (r.redirected) {
            window.location.href = r.url;
        } else {
            location.reload();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="paket"]:checked');
    onPaketChange(checked ? checked.value : 'pro');
});
</script>
@endsection