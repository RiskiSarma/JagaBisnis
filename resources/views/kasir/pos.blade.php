@extends('layouts.app')
@section('title', 'Kasir / POS')
@section('content-class', '')

@section('content')
<div class="flex h-full" x-data="posApp({{ json_encode(['products' => $products, 'promos' => $promos, 'customers' => $customers]) }})">

    {{-- ── PRODUK PANEL ── --}}
    <div class="flex-1 overflow-y-auto p-4 bg-slate-50 dark:bg-slate-900">

        {{-- Search --}}
        <div class="relative mb-3">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2"/>
            </svg>
            <input type="text" x-model="search" placeholder="Cari produk..."
                   class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-brand transition-colors">
        </div>

        {{-- Kategori pills --}}
        <div class="flex gap-2 flex-wrap mb-3">
            <button @click="activeCat = 'Semua'"
                    :class="activeCat === 'Semua' ? 'bg-brand text-white border-brand' : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-600 hover:border-brand hover:text-brand'"
                    class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-all">Semua</button>
            <template x-for="cat in categories" :key="cat">
                <button @click="activeCat = cat"
                        :class="activeCat === cat ? 'bg-brand text-white border-brand' : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-600 hover:border-brand hover:text-brand'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-all"
                        x-text="cat"></button>
            </template>
        </div>

        {{-- Promo banner --}}
        @if($promos->count())
        <div class="flex items-center gap-2 bg-amber-50 border border-amber-300 rounded-xl px-3 py-2 mb-3 text-xs text-amber-800 font-medium flex-wrap">
            <svg class="w-3.5 h-3.5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span><strong>Promo aktif:</strong> {{ $promos->pluck('name')->implode(', ') }}</span>
            <button @click="showPromoModal = true" class="ml-auto bg-amber-500 text-white rounded-lg px-2.5 py-1 text-[11px] font-bold">Pakai</button>
        </div>
        @endif

        {{-- Stok alert --}}
        @if($business->feat_stok)
            @php $lowStk = $products->where('stock_mode','tracked')->where('stock','<=',5) @endphp
            @if($lowStk->count())
            <div class="flex items-start gap-2 bg-amber-50 border border-amber-300 rounded-xl px-3 py-2 mb-3 text-xs text-amber-800">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <strong>Peringatan stok:</strong>
                    {{ $lowStk->map(fn($p) => $p->stock <= 0 ? $p->name.' (habis)' : $p->name.' (sisa '.$p->stock.')')->implode(', ') }}
                </div>
            </div>
            @endif
        @endif

        {{-- Grid produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-2.5">
            <template x-for="product in filteredProducts" :key="product.id">
                <div @click="!product.out && addToCart(product)"
                     :class="{
                         'border-brand bg-brand/5': cartHas(product.id),
                         'opacity-40 cursor-not-allowed': product.out,
                         'border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-brand': !cartHas(product.id) && !product.out
                     }"
                     class="relative border-2 rounded-xl p-3 text-center cursor-pointer transition-all">

                    <span x-show="cartHas(product.id)"
                          x-text="getQty(product.id)"
                          class="absolute -top-2 -right-2 w-5 h-5 bg-brand text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900"></span>

                    <span x-show="product.out"
                          class="absolute top-1.5 right-1.5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">HABIS</span>

                    <div class="relative w-full aspect-square rounded-lg overflow-hidden mb-2 bg-slate-100 dark:bg-slate-700">
                        <template x-if="product.image_url">
                            <img :src="product.image_url" :alt="product.name" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!product.image_url">
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-300 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/>
                                    <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                                </svg>
                            </div>
                        </template>
                        <div class="absolute top-0 left-0 right-0 h-1" :style="'background:' + product.color"></div>
                    </div>

                    <p x-text="product.category" class="text-[9px] text-slate-400 dark:text-slate-500 uppercase font-bold mb-0.5"></p>
                    <p x-text="product.name" class="text-xs font-bold text-slate-900 dark:text-white leading-tight mb-1"></p>
                    <p x-text="formatRp(product.price)" class="text-sm font-extrabold text-brand"></p>

                    <template x-if="product.stock_mode === 'tracked'">
                        <div class="mt-1.5">
                            <div class="h-1 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                                <div class="h-full rounded-full transition-all"
                                     :style="'width:' + Math.min(100, Math.round(product.stock/20*100)) + '%;background:' + (product.out ? '#EF4444' : product.stock <= 5 ? '#F59E0B' : '#10B981')"></div>
                            </div>
                            <p class="text-[10px] mt-0.5 font-semibold"
                               :class="product.out ? 'text-red-500' : product.stock <= 5 ? 'text-amber-500' : 'text-slate-400'"
                               x-text="product.out ? 'Habis' : 'Sisa ' + product.stock"></p>
                        </div>
                    </template>
                    <template x-if="product.stock_mode === 'unlimited'">
                        <p class="text-[10px] text-purple-500 font-bold mt-1">∞</p>
                    </template>
                </div>
            </template>

            {{-- Empty state --}}
            <template x-if="filteredProducts.length === 0">
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-sm font-semibold">Tidak ada produk</p>
                    <p class="text-xs mt-1">Tambah produk di menu Admin → Produk</p>
                </div>
            </template>
        </div>
    </div>

    {{-- ── CART SIDEBAR (desktop) ── --}}
    <div class="w-80 hidden lg:flex flex-col bg-white dark:bg-slate-800 border-l border-slate-100 dark:border-slate-700">

        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" stroke-width="2"/>
                </svg>
                <h2 class="font-extrabold text-sm">Pesanan</h2>
            </div>
            <span x-text="cartCount" class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full"></span>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-2 min-h-0">
            <template x-if="cart.length === 0">
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" stroke-width="2"/>
                    </svg>
                    <p class="text-xs">Pilih produk dari katalog</p>
                </div>
            </template>
            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center gap-2.5 p-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-100 dark:border-slate-600 rounded-xl">
                    <div class="flex-1 min-w-0">
                        <p x-text="item.name" class="text-xs font-semibold text-slate-800 dark:text-white truncate"></p>
                        <p x-text="formatRp(item.price)" class="text-[11px] text-slate-400"></p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <button @click.stop="changeQty(item.id, -1)"
                                class="w-6 h-6 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-500 text-slate-600 dark:text-slate-300 hover:bg-brand hover:text-white hover:border-brand transition-all text-sm font-bold">−</button>
                        <span x-text="item.qty" class="w-6 text-center text-xs font-bold"></span>
                        <button @click.stop="changeQty(item.id, 1)"
                                class="w-6 h-6 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-500 text-slate-600 dark:text-slate-300 hover:bg-brand hover:text-white hover:border-brand transition-all text-sm font-bold">+</button>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-700 space-y-2">
            <div class="flex justify-between text-sm text-slate-500">
                <span>Subtotal</span><span x-text="formatRp(subtotal)"></span>
            </div>
            <div x-show="activePromo && discount > 0" class="flex justify-between text-sm text-emerald-600 font-semibold">
                <span>Diskon (<span x-text="activePromo?.name"></span>)</span>
                <span x-text="'−' + formatRp(discount)"></span>
            </div>
            <div class="flex justify-between font-extrabold text-base">
                <span>TOTAL</span>
                <span x-text="formatRp(total)" class="text-brand"></span>
            </div>

            {{-- Customer --}}
            <div class="relative">
                <svg class="absolute left-3 top-2.5 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke-width="2"/><circle cx="12" cy="7" r="4" stroke-width="2"/>
                </svg>
                <input type="text" x-model="custName"
                       @input="searchCustomers"
                       @focus="searchCustomers"
                       @blur.debounce.300="custDropOpen = false"
                       placeholder="Nama pelanggan (opsional)"
                       class="w-full pl-8 pr-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 outline-none focus:border-brand transition-colors">
                <div x-show="custDropOpen && custSuggestions.length > 0"
                     class="absolute bottom-full left-0 right-0 mb-1 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl shadow-lg z-10 overflow-hidden max-h-40 overflow-y-auto">
                    <template x-for="c in custSuggestions" :key="c.id">
                        <div @mousedown.prevent="selectCustomer(c)"
                             class="flex items-center gap-2.5 px-3 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-600 cursor-pointer border-b border-slate-100 dark:border-slate-600 last:border-0">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-brand to-blue-400 flex items-center justify-center text-white text-[10px] font-bold shrink-0"
                                 x-text="c.name[0]?.toUpperCase()"></div>
                            <div>
                                <p x-text="c.name" class="text-xs font-semibold text-slate-800 dark:text-white"></p>
                                <p x-text="c.phone || '—'" class="text-[10px] text-slate-400"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="relative">
                <svg class="absolute left-3 top-2.5 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.69 12 19.79 19.79 0 011.61 3.38 2 2 0 013.6 1.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.91 8.96a16 16 0 006.13 6.13l1.41-1.41a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" stroke-width="2"/>
                </svg>
                <input type="text" x-model="custPhone" placeholder="No. HP (opsional)"
                       class="w-full pl-8 pr-3 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 outline-none focus:border-brand transition-colors">
            </div>

            <button @click="openPayment()"
                    :disabled="cart.length === 0"
                    class="w-full py-3.5 bg-gradient-to-r from-brand to-blue-400 text-white font-extrabold rounded-xl text-sm transition-all disabled:from-slate-300 disabled:to-slate-400 disabled:cursor-not-allowed hover:enabled:-translate-y-0.5 shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="1" y="4" width="22" height="16" rx="2" stroke-width="2"/><line x1="1" y1="10" x2="23" y2="10" stroke-width="2"/>
                </svg>
                Bayar
            </button>
        </div>
    </div>

    {{-- ── FAB MOBILE ── --}}
    <button x-show="cart.length > 0"
            @click="cartDrawerOpen = true"
            class="lg:hidden fixed bottom-5 right-5 z-20 bg-brand text-white rounded-full px-4 py-3 font-bold text-sm shadow-xl shadow-brand/40 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" stroke-width="2"/>
        </svg>
        <span x-text="cartCount + ' item — ' + formatRp(total)"></span>
    </button>

    {{-- ── PROMO MODAL ── --}}
    <div x-show="showPromoModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
         x-transition.opacity @click.self="showPromoModal = false">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-sm shadow-2xl" @click.stop>
            <div class="p-5">
                <h3 class="text-base font-extrabold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Pilih Promo
                </h3>
                <div class="space-y-2 mb-4">
                    <template x-for="promo in promos" :key="promo.id">
                        <div @click="applyPromo(promo)"
                             :class="activePromo?.id === promo.id ? 'border-brand bg-brand/5' : 'border-slate-200 dark:border-slate-600'"
                             class="border-2 rounded-xl p-3 cursor-pointer transition-all hover:border-brand">
                            <div class="flex items-center justify-between">
                                <p x-text="promo.name" class="font-bold text-sm text-slate-800 dark:text-white"></p>
                                <span x-show="activePromo?.id === promo.id"
                                      class="text-xs bg-brand text-white px-2 py-0.5 rounded-full font-bold">Aktif</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"
                               x-text="promo.type === 'percent' ? 'Diskon ' + promo.value + '%' : 'Potongan Rp ' + Number(promo.value).toLocaleString('id-ID')"></p>
                            <p x-show="promo.min_buy > 0"
                               class="text-[10px] text-amber-600 mt-0.5"
                               x-text="'Min. belanja Rp ' + Number(promo.min_buy).toLocaleString('id-ID')"></p>
                        </div>
                    </template>
                </div>
                <div class="flex gap-2">
                    <button @click="activePromo = null; showPromoModal = false"
                            class="flex-1 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300">
                        Hapus Promo
                    </button>
                    <button @click="showPromoModal = false"
                            class="flex-1 py-2.5 bg-brand text-white rounded-xl text-sm font-extrabold">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── PAYMENT MODAL ── --}}
    <div x-show="paymentModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
         x-transition.opacity @click.self="paymentModal = false">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-2xl" @click.stop>
            <div class="p-5">
                <h3 class="text-base font-extrabold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="1" y="4" width="22" height="16" rx="2" stroke-width="2"/><line x1="1" y1="10" x2="23" y2="10" stroke-width="2"/>
                    </svg>
                    Konfirmasi & Pembayaran
                </h3>

                {{-- Order summary --}}
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-3 mb-4 max-h-36 overflow-y-auto space-y-1.5">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between text-sm">
                            <span x-text="item.name + ' ×' + item.qty" class="text-slate-700 dark:text-slate-300"></span>
                            <span x-text="formatRp(item.price * item.qty)" class="font-semibold text-brand"></span>
                        </div>
                    </template>
                </div>

                {{-- Total bar --}}
                <div class="bg-gradient-to-r from-brand to-blue-400 rounded-xl p-3.5 flex justify-between items-center mb-4">
                    <div>
                        <p class="text-white/70 text-xs">Pelanggan: <span x-text="custName || 'Umum'" class="font-semibold text-white"></span></p>
                        <p x-show="discount > 0" class="text-white/60 text-xs">Diskon: <span x-text="'−' + formatRp(discount)"></span></p>
                    </div>
                    <p x-text="formatRp(total)" class="text-white font-extrabold text-xl"></p>
                </div>

                {{-- Metode bayar --}}
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Metode Pembayaran</p>

                @php $hasMidtrans = auth()->user()->business->hasMidtransConnected(); @endphp

                @if($hasMidtrans)
                {{-- Satu tombol untuk semua pembayaran digital --}}
                <button @click="prosesCheckoutDigital()"
                        :disabled="processing"
                        class="w-full py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 hover:from-emerald-600 hover:to-blue-600 text-white font-bold text-sm flex items-center justify-center gap-3 transition mb-4 shadow-lg shadow-emerald-500/30 disabled:opacity-60 disabled:cursor-not-allowed">
                    <template x-if="!processing">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="1.75"/>
                            <path d="M3 9h18M9 21V9" stroke-width="1.75"/>
                            <rect x="6" y="6" width="3" height="3" fill="currentColor"/>
                            <rect x="15" y="6" width="3" height="3" fill="currentColor"/>
                            <rect x="6" y="15" width="3" height="3" fill="currentColor"/>
                            <rect x="15" y="15" width="3" height="3" fill="currentColor"/>
                        </svg>
                    </template>
                    <template x-if="processing">
                        <svg class="w-5 h-5 animate-spin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" opacity="0.25"/>
                            <path d="M12 2a10 10 0 0110 10" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </template>
                    <div class="text-left">
                        <p class="font-bold" x-text="processing ? 'Memproses...' : 'Bayar Digital'"></p>
                        <p class="text-[10px] text-white/70">QRIS · GoPay · OVO · DANA · Transfer Bank</p>
                    </div>
                </button>
                @else
                <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-700/40 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2.5 mb-4">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="1.75"/>
                        <line x1="12" y1="12" x2="12" y2="16" stroke-width="1.75"/>
                        <line x1="12" y1="8" x2="12.01" y2="8" stroke-width="1.75"/>
                    </svg>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Aktifkan pembayaran digital di
                        <a href="{{ route('admin.midtrans-setting.index') }}" class="text-brand hover:underline font-semibold">Pengaturan Pembayaran</a>
                        untuk QRIS & E-Wallet.
                    </p>
                </div>
                @endif

                {{-- Tunai --}}
                <button @click="payMethod = 'cash'"
                        :class="payMethod === 'cash' ? 'border-brand bg-brand/5' : 'border-slate-200 dark:border-slate-600'"
                        class="w-full border-2 rounded-xl p-3 flex items-center justify-center gap-2.5 transition-all mb-2">
                    <svg class="w-5 h-5" :class="payMethod === 'cash' ? 'text-brand' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="1.75"/>
                        <circle cx="12" cy="12" r="2" stroke-width="1.75"/>
                        <path d="M6 12h.01M18 12h.01" stroke-width="1.75"/>
                    </svg>
                    <p class="text-sm font-bold" :class="payMethod === 'cash' ? 'text-brand' : 'text-slate-600 dark:text-slate-300'">Tunai</p>
                </button>

                {{-- Cash detail --}}
                <div x-show="payMethod === 'cash'" class="bg-slate-50 dark:bg-slate-700 rounded-xl p-3 mb-4">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-1.5">Uang Diterima</label>
                    <input type="number" x-model.number="cashReceived"
                           :placeholder="total"
                           class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-500 rounded-xl font-bold text-base outline-none focus:border-brand bg-white dark:bg-slate-600 text-slate-900 dark:text-white">
                    <div class="flex justify-between items-center mt-2 bg-white dark:bg-slate-600 rounded-lg px-3 py-2">
                        <span class="text-xs text-slate-500">Kembalian</span>
                        <span x-text="cashReceived > 0 ? (cashReceived >= total ? formatRp(cashReceived - total) : 'Kurang ' + formatRp(total - cashReceived)) : '—'"
                              :class="cashReceived > 0 && cashReceived < total ? 'text-red-500' : 'text-emerald-600'"
                              class="font-extrabold text-sm"></span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button @click="paymentModal = false"
                            class="flex-1 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:border-slate-300">
                        Batal
                    </button>
                    <button @click="submitCheckout()" :disabled="processing || payMethod !== 'cash'"
                            class="flex-2 flex-grow py-2.5 bg-brand text-white rounded-xl text-sm font-extrabold disabled:opacity-60 disabled:cursor-not-allowed hover:bg-brand-dark transition-all">
                        <span x-show="!processing">✓ Konfirmasi Bayar</span>
                        <span x-show="processing">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SUCCESS MODAL / RECEIPT ── --}}
    <div x-show="receiptModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
         x-transition.opacity @click.self="closeReceipt()">
        <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-sm max-h-[90vh] overflow-y-auto shadow-2xl" @click.stop>
            <div class="p-5">
                <div class="text-center mb-3">
                    <div class="inline-flex p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-full mb-2">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-base">Pembayaran Berhasil!</h3>
                </div>

                <div x-show="lastTx" class="border border-dashed border-slate-200 dark:border-slate-600 rounded-xl p-4 bg-slate-50 dark:bg-slate-700 text-xs font-mono mb-3">
                    <div class="text-center mb-3">
                        <p class="font-extrabold text-sm" x-text="lastTx?.business?.name"></p>
                        <p class="text-slate-400 text-[10px]" x-text="lastTx?.created_at"></p>
                    </div>
                    <hr class="border-dashed border-slate-300 dark:border-slate-500 mb-2">
                    <template x-for="item in (lastTx?.items || [])">
                        <div class="flex justify-between mb-1">
                            <span x-text="item.name + ' ×' + item.qty"></span>
                            <span x-text="formatRp(item.price * item.qty)" class="font-semibold"></span>
                        </div>
                    </template>
                    <hr class="border-dashed border-slate-300 dark:border-slate-500 my-2">
                    <div x-show="lastTx?.discount > 0" class="flex justify-between text-emerald-600 text-[11px] mb-1">
                        <span>Diskon</span>
                        <span x-text="'−' + formatRp(lastTx?.discount)"></span>
                    </div>
                    <div class="flex justify-between font-extrabold text-sm">
                        <span>TOTAL</span>
                        <span x-text="formatRp(lastTx?.total)" class="text-brand"></span>
                    </div>
                    <div x-show="lastTx?.pay_method === 'cash' && lastTx?.cash_change > 0" class="flex justify-between text-emerald-600 font-semibold mt-1">
                        <span>Kembalian</span>
                        <span x-text="formatRp(lastTx?.cash_change)"></span>
                    </div>
                    <hr class="border-dashed border-slate-300 dark:border-slate-500 my-2">
                    <p class="text-center text-slate-400">🙏 Terima kasih sudah berbelanja!</p>
                </div>

                <div x-show="lastTx?.customer?.phone" class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl p-3 mb-3">
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 mb-2">Kirim nota via WhatsApp</p>
                    <input type="text" x-model="waExtra" placeholder="Pesan tambahan (opsional)..."
                           class="w-full px-3 py-2 border border-emerald-200 rounded-lg text-xs bg-white dark:bg-slate-700 outline-none mb-2">
                    <button @click="sendWA()" class="w-full py-2 bg-[#25D366] text-white rounded-lg text-xs font-bold flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" stroke-width="2"/>
                        </svg>
                        Kirim Nota WA
                    </button>
                </div>

                <div class="flex gap-2">
                    <a :href="lastTx ? '/kasir/receipt/' + lastTx.id + '/pdf' : '#'" target="_blank"
                       class="flex-1 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-semibold text-center text-slate-600 dark:text-slate-300 hover:border-slate-300">
                        📄 PDF
                    </a>
                    <button @click="closeReceipt()" class="flex-grow py-2.5 bg-brand text-white rounded-xl text-sm font-extrabold hover:bg-brand-dark transition-all">
                        + Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function posApp(data) {
    return {
        allProducts:     data.products.map(p => ({
            ...p,
            out: p.stock_mode === 'tracked' && p.stock <= 0
        })),
        promos:          data.promos,
        search:          '',
        activeCat:       'Semua',
        cart:            [],
        custName:        '',
        custPhone:       '',
        custDropOpen:    false,
        custSuggestions: [],
        activePromo:     null,
        payMethod:       'cash',
        cashReceived:    '',
        paymentModal:    false,
        receiptModal:    false,
        lastTx:          null,
        processing:      false,
        showPromoModal:  false,
        cartDrawerOpen:  false,
        waExtra:         '',

        get categories() {
            return [...new Set(this.allProducts.map(p => p.category))];
        },
        get filteredProducts() {
            return this.allProducts.filter(p => {
                const matchCat    = this.activeCat === 'Semua' || p.category === this.activeCat;
                const matchSearch = p.name.toLowerCase().includes(this.search.toLowerCase());
                return matchCat && matchSearch;
            });
        },
        get cartCount() { return this.cart.reduce((s, i) => s + i.qty, 0); },
        get subtotal()  { return this.cart.reduce((s, i) => s + i.price * i.qty, 0); },
        get discount() {
            if (!this.activePromo) return 0;
            const p = this.activePromo;
            if (p.min_buy > 0 && this.subtotal < p.min_buy) return 0;
            return p.type === 'percent'
                ? Math.round(this.subtotal * p.value / 100)
                : p.value;
        },
        get total() { return this.subtotal - this.discount; },

        addToCart(product) {
            if (product.out) return;
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                if (product.stock_mode === 'tracked' && existing.qty >= product.stock) {
                    alert('Stok tidak mencukupi!');
                    return;
                }
                existing.qty++;
            } else {
                this.cart.push({ ...product, qty: 1 });
            }
            this.cart = [...this.cart];
        },

        changeQty(id, delta) {
            const idx = this.cart.findIndex(i => i.id === id);
            if (idx === -1) return;
            this.cart[idx].qty += delta;
            if (this.cart[idx].qty <= 0) this.cart.splice(idx, 1);
        },

        cartHas(id) { return this.cart.some(i => i.id === id); },
        getQty(id)  { return this.cart.find(i => i.id === id)?.qty ?? 0; },
        formatRp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); },

        applyPromo(promo) {
            this.activePromo    = this.activePromo?.id === promo.id ? null : promo;
            this.showPromoModal = false;
        },

        async searchCustomers() {
            if (this.custName.length < 1) {
                this.custSuggestions = [];
                this.custDropOpen    = false;
                return;
            }
            const res = await fetch(`/kasir/customers/search?q=${encodeURIComponent(this.custName)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            this.custSuggestions = await res.json();
            this.custDropOpen    = this.custSuggestions.length > 0;
        },

        selectCustomer(c) {
            this.custName     = c.name;
            this.custPhone    = c.phone ?? '';
            this.custDropOpen = false;
        },

        openPayment() {
            if (this.cart.length === 0) return;
            this.cashReceived = '';
            this.payMethod    = 'cash';
            this.paymentModal = true;
        },

        async submitCheckout() {
            if (this.payMethod !== 'cash') return;
            if (this.cashReceived !== '' && Number(this.cashReceived) < this.total) {
                alert('Uang diterima kurang dari total!');
                return;
            }
            this.processing = true;
            try {
                const res = await fetch('/kasir/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'X-CSRF-TOKEN':  document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        items:          this.cart.map(i => ({ id: i.id, qty: i.qty })),
                        customer_name:  this.custName,
                        customer_phone: this.custPhone,
                        promo_id:       this.activePromo?.id ?? null,
                        pay_method:     'cash',
                        payment_method: 'manual',
                        cash_received:  this.cashReceived ? Number(this.cashReceived) : this.total,
                    })
                });
                const data = await res.json();
                if (data.success) {
                    this.lastTx       = data.transaction;
                    this.paymentModal = false;
                    this.receiptModal = true;
                    this._resetCart();
                } else {
                    alert(data.message ?? 'Terjadi kesalahan');
                }
            } catch (e) {
                alert('Gagal terhubung ke server');
            }
            this.processing = false;
        },

        async prosesCheckoutDigital() {
            if (this.cart.length === 0) return;

            this.processing = true;

            try {
                // Step 1: Buat transaksi dengan status pending
                const checkoutRes = await fetch('/kasir/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({
                        items:          this.cart.map(i => ({ id: i.id, qty: i.qty })),
                        customer_name:  this.custName,
                        customer_phone: this.custPhone,
                        promo_id:       this.activePromo?.id ?? null,
                        pay_method:     'transfer',
                        payment_method: 'midtrans',
                        cash_received:  this.total,
                    })
                });

                const checkoutData = await checkoutRes.json();
                if (!checkoutData.success) {
                    alert(checkoutData.message || 'Gagal membuat transaksi.');
                    this.processing = false;
                    return;
                }

                // Step 2: Ambil Snap Token
                const snapRes = await fetch('/kasir/pos/snap-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({ transaction_id: checkoutData.transaction_id })
                });

                const snapData = await snapRes.json();
                if (!snapData.success) {
                    alert(snapData.message || 'Gagal memuat payment gateway.');
                    this.processing = false;
                    return;
                }

                // Step 3: Load Snap.js & buka popup
                const snapSrc = snapData.is_production
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js';

                this.loadSnapScript(snapSrc, snapData.client_key, () => {
                    this.paymentModal = false;
                    this.processing   = false;

                    window.snap.pay(snapData.snap_token, {
                        onSuccess: () => {
                            this.lastTx       = checkoutData.transaction;
                            this.receiptModal = true;
                            this._resetCart();
                        },
                        onPending: () => {
                            alert('Menunggu pembayaran. Status akan diperbarui otomatis via webhook.');
                            this._resetCart();
                        },
                        onError: () => {
                            alert('Pembayaran gagal. Silakan coba lagi.');
                            this.paymentModal = true;
                        },
                        onClose: () => {
                            // User tutup tanpa bayar, biarkan pilih metode lain
                            this.paymentModal = true;
                        }
                    });
                });

            } catch (err) {
                console.error('Digital checkout error:', err);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                this.processing   = false;
                this.paymentModal = true;
            }
        },

        loadSnapScript(src, clientKey, callback) {
            const old = document.getElementById('midtrans-snap-script');
            if (old) old.remove();
            const script = document.createElement('script');
            script.id    = 'midtrans-snap-script';
            script.src   = src;
            script.setAttribute('data-client-key', clientKey);
            script.onload = callback;
            document.head.appendChild(script);
        },

        sendWA() {
            if (!this.lastTx?.customer?.phone) return;
            const t     = this.lastTx;
            const ph    = t.customer.phone.replace(/[\s\-().]/g, '').replace(/^0/, '62');
            const items = t.items.map(i => `  • ${i.name} ×${i.qty}  = Rp${(i.price * i.qty).toLocaleString('id-ID')}`).join('\n');
            const disc  = t.discount > 0 ? `\n🏷️ Diskon: -Rp${t.discount.toLocaleString('id-ID')}` : '';
            const msg   = `🧾 *NOTA BELANJA*\n━━━━━━━━━━━━━━━\n🏪 *${t.business?.name ?? ''}*\n📅 ${new Date(t.created_at).toLocaleString('id-ID')}\n━━━━━━━━━━━━━━━\n${items}\n━━━━━━━━━━━━━━━${disc}\n💰 *TOTAL: Rp${t.total.toLocaleString('id-ID')}*\n━━━━━━━━━━━━━━━\n${this.waExtra ? '💬 ' + this.waExtra + '\n\n' : ''}🙏 Terima kasih sudah berbelanja!`;
            window.open(`https://wa.me/${ph}?text=${encodeURIComponent(msg)}`, '_blank');
        },

        _resetCart() {
            this.cart         = [];
            this.activePromo  = null;
            this.custName     = '';
            this.custPhone    = '';
            this.cashReceived = '';
        },

        closeReceipt() {
            this.receiptModal = false;
            this.lastTx       = null;
            this.waExtra      = '';
        },
    }
}
</script>
@endpush