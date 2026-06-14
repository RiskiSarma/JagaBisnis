@extends('layouts.app')
@section('title', 'Set Promo')
@section('content')

<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Set Promo</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kelola promosi untuk bisnis Anda.</p>
    </div>
    <button type="button" onclick="bukaModalPromo()"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Buat Promo
    </button>
</div>

@if($promos->isEmpty())
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-10 text-center">
    <div class="inline-flex p-4 bg-slate-100 dark:bg-slate-700/50 rounded-full mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
    </div>
    <p class="text-sm text-slate-400">Belum ada promo. Buat promo pertama Anda!</p>
</div>
@else
<div class="space-y-3">
    @foreach($promos as $promo)
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        </div>

        <div class="flex-1 min-w-0">
            <div class="font-bold text-slate-800 dark:text-white">{{ $promo->name }}</div>
            @if($promo->description ?? false)
            <div class="text-xs text-slate-400 mt-0.5">{{ $promo->description }}</div>
            @endif
            <div class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-1">
                {{ $promo->type === 'percent' ? $promo->value.'% diskon' : 'Potongan Rp '.number_format($promo->value,0,',','.') }}
                @if($promo->min_buy)
                    &bull; Min. Rp {{ number_format($promo->min_buy,0,',','.') }}
                @endif
                &bull; Kode: <code class="bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded text-slate-600 dark:text-slate-300">{{ $promo->code }}</code>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $promo->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                {{ $promo->status === 'active' ? 'Aktif' : 'Nonaktif' }}
            </span>

            <form method="POST" action="{{ route('admin.promos.toggle', $promo) }}" class="inline">
                @csrf @method('PATCH')
                <label class="relative inline-block w-10 h-5 cursor-pointer">
                    <input type="checkbox" onchange="this.form.submit()" {{ $promo->status === 'active' ? 'checked' : '' }} class="opacity-0 w-0 h-0 peer">
                    <span class="absolute inset-0 rounded-full bg-slate-300 dark:bg-slate-600 peer-checked:bg-emerald-500 transition-colors"></span>
                    <span class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></span>
                </label>
            </form>

            <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" class="inline" onsubmit="return confirm('Hapus promo ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Modal Buat Promo --}}
<div id="promoOverlay"
     class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this)tutupModalPromo()">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-base font-extrabold text-slate-800 dark:text-white mb-5">Buat Promo Baru</h3>

        <form action="{{ route('admin.promos.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nama Promo</label>
                <input type="text" name="name" required placeholder="cth: Promo Weekend"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                <input type="text" name="description" placeholder="Keterangan promo"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Tipe</label>
                    <select name="type" id="promoType" onchange="updateValueLabel()"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
                        <option value="percent">Persen (%)</option>
                        <option value="flat">Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label id="promoValueLabel" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nilai (%)</label>
                    <input type="number" name="value" min="1" required placeholder="15"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Min. Pembelian</label>
                    <input type="number" name="min_buy" min="0" placeholder="0"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Kode Promo</label>
                    <input type="text" name="code" required placeholder="PROMO2025"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200 uppercase">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="tutupModalPromo()"
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

@push('scripts')
<script>
function bukaModalPromo() {
    document.getElementById('promoOverlay').classList.remove('hidden');
}
function tutupModalPromo() {
    document.getElementById('promoOverlay').classList.add('hidden');
}
function updateValueLabel() {
    var type = document.getElementById('promoType').value;
    document.getElementById('promoValueLabel').textContent = type === 'percent' ? 'Nilai (%)' : 'Nilai (Rp)';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModalPromo();
});

@if($errors->any())
    bukaModalPromo();
@endif
</script>
@endpush

@endsection