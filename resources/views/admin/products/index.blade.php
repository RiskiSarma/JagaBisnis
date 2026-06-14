@extends('layouts.app')
@section('title', 'Produk')
@section('content')

{{-- Page Header --}}
<div class="flex items-start justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Kelola Produk</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            {{ $products->count() }} produk terdaftar untuk {{ $business->name }}
        </p>
    </div>
    <button onclick="document.getElementById('modal-add-product').classList.remove('hidden')"
        class="inline-flex items-center gap-2 px-4 py-2 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-dark transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Produk
    </button>
</div>

{{-- Low Stock Alert --}}
@php $lowStockProds = $products->where('stock_mode','tracked')->filter(fn($p) => $p->stock <= 5); @endphp
@if($lowStockProds->count())
<div class="flex items-start gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl mb-5">
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 shrink-0"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <div class="flex-1">
        <p class="text-xs font-bold text-amber-800 dark:text-amber-300">Peringatan Stok</p>
        <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">
            @php $out = $lowStockProds->where('stock',0); $low = $lowStockProds->where('stock','>',0); @endphp
            @if($out->count()) <strong>Habis:</strong> {{ $out->pluck('name')->join(', ') }} @endif
            @if($out->count() && $low->count()) &bull; @endif
            @if($low->count()) <strong>Hampir habis:</strong> {{ $low->map(fn($p)=>$p->name.' ('.$p->stock.')')->join(', ') }} @endif
        </p>
    </div>
</div>
@endif

{{-- Product Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    @forelse($products as $product)
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden hover:-translate-y-0.5 transition-all duration-200 hover:shadow-lg group">
        {{-- Product Image / Color bar fallback --}}
        @if($product->image)
            <div class="relative w-full aspect-square bg-slate-100 dark:bg-slate-900 overflow-hidden">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
                <div class="absolute top-0 left-0 right-0 h-1" style="background:{{ $product->color ?? '#1A56DB' }}"></div>
            </div>
        @else
            <div class="relative w-full aspect-square bg-slate-50 dark:bg-slate-900 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 dark:text-slate-600"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                <div class="absolute top-0 left-0 right-0 h-1" style="background:{{ $product->color ?? '#1A56DB' }}"></div>
            </div>
        @endif
        <div class="p-4">
            {{-- Category --}}
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">{{ $product->category }}</p>
            {{-- Name --}}
            <p class="text-sm font-bold text-slate-800 dark:text-white mb-1.5 leading-snug line-clamp-2">{{ $product->name }}</p>
            {{-- Price --}}
            <p class="text-base font-extrabold text-brand font-mono">Rp {{ number_format($product->price,0,',','.') }}</p>
            {{-- Stock badge --}}
            @if($product->stock_mode === 'unlimited')
                <span class="inline-flex items-center mt-2 gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-400">
                    <span>∞</span> Tidak Terbatas
                </span>
            @elseif($product->stock_mode === 'tracked')
                @if(($product->stock ?? 0) <= 0)
                    <span class="inline-flex items-center mt-2 gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Habis
                    </span>
                @elseif($product->stock <= 5)
                    <span class="inline-flex items-center mt-2 gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Stok: {{ $product->stock }}
                    </span>
                @else
                    <span class="inline-flex items-center mt-2 gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Stok: {{ $product->stock }}
                    </span>
                @endif
            @else
                <span class="inline-flex items-center mt-2 gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                    ✏️ Manual
                </span>
            @endif
            {{-- Actions --}}
            <div class="flex gap-2 mt-3 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button onclick="openEditProduct({{ $product->id }},'{{ addslashes($product->name) }}',{{ $product->price }},{{ $product->stock ?? 0 }},'{{ $product->stock_mode }}','{{ addslashes($product->category) }}','{{ $product->color ?? '#1A56DB' }}','{{ $product->image ? asset('storage/'.$product->image) : '' }}')"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-brand hover:text-brand hover:bg-brand/5 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </button>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                      onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-500 hover:text-white border border-red-200 dark:border-red-800 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6M9 6V4h6v2"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-16 text-center">
        <div class="inline-flex flex-col items-center gap-3 text-slate-400 dark:text-slate-500">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </div>
            <p class="text-sm font-medium">Belum ada produk</p>
            <button onclick="document.getElementById('modal-add-product').classList.remove('hidden')"
                class="text-xs text-brand font-semibold hover:underline">+ Tambah produk pertama</button>
        </div>
    </div>
    @endforelse
</div>

{{-- ══ MODAL: Tambah Produk ══ --}}
<div id="modal-add-product" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-brand/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100">Tambah Produk</h3>
                </div>
                <button type="button" onclick="document.getElementById('modal-add-product').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                {{-- Upload Gambar --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Foto Produk</label>
                    <div class="flex items-center gap-3">
                        <div id="add-preview-wrap" class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 flex items-center justify-center overflow-hidden shrink-0">
                            <img id="add-preview-img" class="hidden w-full h-full object-cover" alt="preview">
                            <svg id="add-preview-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 dark:text-slate-600"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp"
                                onchange="previewImage(this,'add-preview-img','add-preview-icon')"
                                class="w-full text-xs text-slate-500 dark:text-slate-400
                                    file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                    file:text-xs file:font-semibold file:bg-brand/10 file:text-brand
                                    hover:file:bg-brand/20 file:cursor-pointer cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">Maks 1MB. Gambar otomatis dikompres.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Nama Produk</label>
                    <input type="text" name="name" required placeholder="cth: Es Kopi Susu"
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Harga (Rp)</label>
                        <input type="number" name="price" required placeholder="15000" min="0"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Stok Awal</label>
                        <input type="number" name="stock" placeholder="0" min="0"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Kategori</label>
                        <select name="category" id="add-category-select" required
                            onchange="toggleNewCategory(this,'add-new-category-wrap','add-new-category-input')"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                            <option value="__new__">+ Kategori Baru...</option>
                        </select>
                        <div id="add-new-category-wrap" class="hidden mt-2">
                            <input type="text" id="add-new-category-input" placeholder="Nama kategori baru"
                                oninput="syncNewCategory(this,'add-category-select')"
                                class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Warna Label</label>
                        <input type="color" name="color" value="#1A56DB"
                            class="w-full h-[42px] px-1.5 py-1.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer">
                    </div>
                </div>
                {{-- Mode Stok --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">Mode Stok</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([
                            ['tracked',  '#10B981', 'bg-emerald-50 dark:bg-emerald-900/20', 'text-emerald-600', 'border-emerald-300', '<path d="M3 3h18v18H3z" opacity=".2"/><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>', 'Dipantau',       'Stok berkurang otomatis'],
                            ['unlimited','#8B5CF6', 'bg-violet-50 dark:bg-violet-900/20',   'text-violet-600',  'border-violet-300',  '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>','Tidak Terbatas','Selalu tersedia'],
                            ['manual',   '#F59E0B', 'bg-amber-50 dark:bg-amber-900/20',     'text-amber-600',   'border-amber-300',   '<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>',  'Manual',         'Update stok sendiri'],
                        ] as [$val, $color, $bg, $textColor, $borderColor, $svgPath, $lbl, $sub])
                        <label class="relative border-2 rounded-xl p-3 text-center cursor-pointer transition-all
                            has-[:checked]:border-current has-[:checked]:{{ $bg }}
                            border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500
                            {{ $val==='tracked' ? $textColor : '' }}">
                            <input type="radio" name="stock_mode" value="{{ $val }}" {{ $val==='tracked'?'checked':'' }} class="sr-only">
                            <div class="w-8 h-8 rounded-lg {{ $bg }} flex items-center justify-center mx-auto mb-2">
                                <svg class="w-4 h-4 {{ $textColor }}" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $svgPath !!}</svg>
                            </div>
                            <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ $lbl }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 leading-tight">{{ $sub }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- Modal footer --}}
            <div class="flex justify-end gap-2 px-6 pb-5">
                <button type="button" onclick="document.getElementById('modal-add-product').classList.add('hidden')"
                    class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 text-sm font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Edit Produk ══ --}}
<div id="modal-edit-product" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <form id="form-edit-product" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100">Edit Produk</h3>
                </div>
                <button type="button" onclick="document.getElementById('modal-edit-product').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                {{-- Upload Gambar --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Foto Produk</label>
                    <div class="flex items-center gap-3">
                        <div id="edit-preview-wrap" class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 flex items-center justify-center overflow-hidden shrink-0 relative">
                            <img id="edit-preview-img" class="hidden w-full h-full object-cover" alt="preview">
                            <svg id="edit-preview-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 dark:text-slate-600"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/png,image/jpeg,image/jpg,image/webp"
                                onchange="previewImage(this,'edit-preview-img','edit-preview-icon'); document.getElementById('ep-remove-image').checked=false;"
                                class="w-full text-xs text-slate-500 dark:text-slate-400
                                    file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                    file:text-xs file:font-semibold file:bg-brand/10 file:text-brand
                                    hover:file:bg-brand/20 file:cursor-pointer cursor-pointer">
                            <label class="flex items-center gap-1.5 mt-1.5 text-[11px] text-slate-500 dark:text-slate-400 cursor-pointer">
                                <input type="checkbox" id="ep-remove-image" name="remove_image" value="1"
                                    onchange="if(this.checked){document.getElementById('edit-preview-img').classList.add('hidden');document.getElementById('edit-preview-icon').classList.remove('hidden');}"
                                    class="rounded border-slate-300 text-red-500 focus:ring-red-400">
                                Hapus gambar saat ini
                            </label>
                            <p class="text-[10px] text-slate-400 mt-1">Maks 1MB. Gambar otomatis dikompres.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Nama Produk</label>
                    <input type="text" id="ep-name" name="name" required
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Harga (Rp)</label>
                        <input type="number" id="ep-price" name="price" required min="0"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Stok</label>
                        <input type="number" id="ep-stock" name="stock" min="0"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Kategori</label>
                        <select id="ep-category-select" name="category" required
                            onchange="toggleNewCategory(this,'edit-new-category-wrap','edit-new-category-input')"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                            <option value="__new__">+ Kategori Baru...</option>
                        </select>
                        <div id="edit-new-category-wrap" class="hidden mt-2">
                            <input type="text" id="edit-new-category-input" placeholder="Nama kategori baru"
                                oninput="syncNewCategory(this,'ep-category-select')"
                                class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Warna Label</label>
                        <input type="color" id="ep-color" name="color"
                            class="w-full h-[42px] px-1.5 py-1.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">Mode Stok</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([
                            ['tracked',  'bg-emerald-50 dark:bg-emerald-900/20', 'text-emerald-600', '<path d="M3 3h18v18H3z" opacity=".2"/><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>', 'Dipantau',        'Berkurang otomatis'],
                            ['unlimited','bg-violet-50 dark:bg-violet-900/20',   'text-violet-600',  '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>','Tidak Terbatas','Selalu tersedia'],
                            ['manual',   'bg-amber-50 dark:bg-amber-900/20',     'text-amber-600',   '<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>',  'Manual',          'Update sendiri'],
                        ] as [$val, $bg, $textColor, $svgPath, $lbl, $sub])
                        <label class="border-2 rounded-xl p-3 text-center cursor-pointer transition-all has-[:checked]:border-brand has-[:checked]:bg-brand/5 border-slate-200 dark:border-slate-600">
                            <input type="radio" id="ep-mode-{{ $val }}" name="stock_mode" value="{{ $val }}" class="sr-only">
                            <div class="w-8 h-8 rounded-lg {{ $bg }} flex items-center justify-center mx-auto mb-2">
                                <svg class="w-4 h-4 {{ $textColor }}" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $svgPath !!}</svg>
                            </div>
                            <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200">{{ $lbl }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 leading-tight">{{ $sub }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-6 pb-5">
                <button type="button" onclick="document.getElementById('modal-edit-product').classList.add('hidden')"
                    class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 text-sm font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input, imgId, iconId) {
    const file = input.files[0];
    const img = document.getElementById(imgId);
    const icon = document.getElementById(iconId);
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        img.src = e.target.result;
        img.classList.remove('hidden');
        icon.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

function toggleNewCategory(select, wrapId, inputId) {
    const wrap = document.getElementById(wrapId);
    const input = document.getElementById(inputId);
    if (select.value === '__new__') {
        wrap.classList.remove('hidden');
        input.required = true;
        input.value = '';
        input.focus();
    } else {
        wrap.classList.add('hidden');
        input.required = false;
        input.value = '';
    }
}

function syncNewCategory(input, selectId) {
    const select = document.getElementById(selectId);
    let tempOption = select.querySelector('option[data-temp="1"]');
    if (!tempOption) {
        tempOption = document.createElement('option');
        tempOption.setAttribute('data-temp', '1');
        select.appendChild(tempOption);
    }
    tempOption.value = input.value;
    tempOption.textContent = input.value;
    tempOption.selected = true;
}

function openEditProduct(id, name, price, stock, mode, category, color, imageUrl) {
    document.getElementById('ep-name').value  = name;
    document.getElementById('ep-price').value = price;
    document.getElementById('ep-stock').value = stock;

    const catSelect = document.getElementById('ep-category-select');
    const newWrap   = document.getElementById('edit-new-category-wrap');
    const newInput  = document.getElementById('edit-new-category-input');

    // bersihkan opsi sementara lama
    const oldTemp = catSelect.querySelector('option[data-temp="1"]');
    if (oldTemp) oldTemp.remove();

    newInput.value = '';
    newWrap.classList.add('hidden');
    newInput.required = false;

    const existingOption = [...catSelect.options].find(o => o.value === category);
    if (existingOption) {
        catSelect.value = category;
    } else {
        // kategori produk ini tidak ada di list -> buat opsi sementara
        const tempOption = document.createElement('option');
        tempOption.setAttribute('data-temp', '1');
        tempOption.value = category;
        tempOption.textContent = category;
        catSelect.appendChild(tempOption);
        catSelect.value = category;
    }

    document.getElementById('ep-color').value = color;
    const radio = document.getElementById('ep-mode-' + mode);
    if (radio) radio.checked = true;

    document.getElementById('ep-remove-image').checked = false;
    const previewImg  = document.getElementById('edit-preview-img');
    const previewIcon = document.getElementById('edit-preview-icon');
    const fileInput   = document.querySelector('#form-edit-product input[name="image"]');
    fileInput.value = '';

    if (imageUrl) {
        previewImg.src = imageUrl;
        previewImg.classList.remove('hidden');
        previewIcon.classList.add('hidden');
    } else {
        previewImg.classList.add('hidden');
        previewIcon.classList.remove('hidden');
    }

    document.getElementById('form-edit-product').action = '/admin/products/' + id;
    document.getElementById('modal-edit-product').classList.remove('hidden');
}

['modal-add-product','modal-edit-product'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endpush

@endsection