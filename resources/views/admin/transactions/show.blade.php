@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('content')

{{-- Page Header --}}
<div class="flex items-start justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Detail Transaksi</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            TXN-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }} &bull; {{ $transaction->created_at->format('d M Y H:i') }} WIB
        </p>
    </div>
    <a href="{{ route('admin.transactions.index') }}"
       class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-lg hover:border-brand hover:text-brand transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali
    </a>
</div>

<div class="grid gap-4 xl:grid-cols-[1fr_340px]">

    {{-- ── KIRI: Struk & Info ── --}}
    <div class="space-y-4">

        {{-- Receipt card --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Struk Transaksi</h3>

            {{-- Receipt area --}}
            <div class="border border-dashed border-slate-200 dark:border-slate-600 rounded-xl p-5 bg-slate-50 dark:bg-slate-900/50 font-mono text-sm">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <p class="text-base font-extrabold text-slate-800 dark:text-white">{{ $transaction->business?->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $transaction->created_at->format('d M Y H:i') }} WIB &bull;
                        TXN-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}
                    </p>
                    @if($transaction->customer?->phone)
                    <p class="text-xs text-slate-400">HP: {{ $transaction->customer->phone }}</p>
                    @endif
                </div>

                <hr class="border-dashed border-slate-200 dark:border-slate-600 mb-3">

                {{-- Items --}}
                @php
                    $items = is_string($transaction->getRawOriginal('items'))
                        ? json_decode($transaction->getRawOriginal('items'), true) ?? []
                        : (is_array($transaction->items) ? $transaction->items : []);
                @endphp
                @foreach($items as $item)
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="text-slate-600 dark:text-slate-300">
                        {{ $item['name'] ?? '' }} &times;{{ $item['qty'] ?? 1 }}
                    </span>
                    <span class="font-semibold text-slate-800 dark:text-white">
                        Rp {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 0, ',', '.') }}
                    </span>
                </div>
                @endforeach

                <hr class="border-dashed border-slate-200 dark:border-slate-600 my-3">

                {{-- Discount --}}
                @if($transaction->discount > 0)
                <div class="flex justify-between text-xs mb-1.5 text-emerald-600 dark:text-emerald-400">
                    <span>Diskon</span>
                    <span class="font-semibold">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif

                {{-- Total --}}
                <div class="flex justify-between text-sm font-extrabold text-slate-900 dark:text-white mt-2 pt-2 border-t border-dashed border-slate-200 dark:border-slate-600">
                    <span>TOTAL</span>
                    <span class="font-mono">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>

                {{-- Cash change --}}
                @if($transaction->pay_method === 'cash' && $transaction->cash_received > $transaction->total)
                <div class="mt-2 space-y-1">
                    <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Uang Diterima</span>
                        <span class="font-mono">Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Kembalian</span>
                        <span class="font-mono">Rp {{ number_format($transaction->cash_change, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif

                <hr class="border-dashed border-slate-200 dark:border-slate-600 my-3">

                {{-- Meta --}}
                <div class="text-[11px] text-slate-400 dark:text-slate-500 space-y-0.5">
                    <p>Pelanggan: {{ $transaction->customer?->name ?? 'Pelanggan' }}</p>
                    <p>Kasir: {{ $transaction->kasir?->name ?? '-' }}</p>
                    <p>Metode: {{ $transaction->pay_method === 'transfer' ? 'Transfer Bank' : 'Tunai (Cash)' }}</p>
                </div>

                <p class="text-center text-xs text-emerald-600 dark:text-emerald-400 font-bold mt-4">
                    🙏 Terima kasih sudah berbelanja!
                </p>
            </div>

            {{-- Catatan --}}
            @if($transaction->catatan)
            <div class="mt-4 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl">
                <p class="text-[11px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wide mb-1">📝 Catatan</p>
                <p class="text-sm text-amber-800 dark:text-amber-300">{{ $transaction->catatan }}</p>
            </div>
            @endif
        </div>

        {{-- Info grid --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Informasi Transaksi</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Waktu</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $transaction->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-slate-400">{{ $transaction->created_at->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Pelanggan</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $transaction->customer?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Kasir</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $transaction->kasir?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Metode</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">
                        {{ $transaction->pay_method === 'transfer' ? 'Transfer' : 'Tunai' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── KANAN: Actions ── --}}
    <div class="space-y-4">

        {{-- Status badge --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">Status Saat Ini</p>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold
                {{ $transaction->status === 'lunas'
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400'
                    : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' }}">
                @if($transaction->status === 'lunas')
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                @else
                    !
                @endif
                {{ $transaction->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
            </span>
        </div>

        {{-- Toggle cepat --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Toggle Status Cepat</h3>
            <form action="{{ route('admin.transactions.toggle', $transaction) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold transition-colors
                        {{ $transaction->status === 'lunas'
                            ? 'bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-400'
                            : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                    Ubah ke {{ $transaction->status === 'lunas' ? 'Belum Lunas' : 'Lunas' }}
                </button>
            </form>
        </div>

        {{-- Edit status & catatan --}}
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Ubah Status & Catatan</h3>
            <form action="{{ route('admin.transactions.update', $transaction) }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
                        <option value="lunas" {{ $transaction->status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_lunas" {{ $transaction->status === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="3"
                        placeholder="cth: tanpa gula, meja 5..."
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand resize-none">{{ $transaction->catatan }}</textarea>
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-brand text-white text-sm font-bold rounded-xl hover:bg-brand-dark transition-colors">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- WA --}}
        @if($transaction->customer?->phone)
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Kirim Struk WA</h3>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',preg_replace('/^0/','62',$transaction->customer->phone)) }}?text={{ urlencode('Halo '.$transaction->customer->name.'! Berikut struk belanja Anda di '.($transaction->business?->name).' — Total: Rp'.number_format($transaction->total,0,',','.').'. Terima kasih!') }}"
               target="_blank"
               class="w-full inline-flex items-center justify-center gap-2 py-2.5 bg-[#25D366] text-white text-sm font-bold rounded-xl hover:bg-[#1fba59] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                Kirim via WhatsApp
            </a>
        </div>
        @endif
    </div>
</div>

@endsection