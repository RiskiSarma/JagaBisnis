@php
function navLink($route, $label, $active = false) {
    // Function ini tidak digunakan lagi, kita pakai inline classes
    return '';
}
@endphp

@php $business = auth()->user()->business; @endphp
 
<p class="px-2 pt-3 pb-1 text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest">Bisnis Saya</p>
 
<a href="{{ route('admin.dashboard') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.dashboard') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
    </svg>
    <span>Dashboard</span>
</a>

<a href="{{ route('admin.products.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.products*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
    </svg>
    <span>Produk</span>
</a>

<a href="{{ route('admin.transactions.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.transactions*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z"/>
        <line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/>
    </svg>
    <span>Transaksi</span>
</a>
 
<p class="px-2 pt-4 pb-1 text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest">Laporan</p>

<a href="{{ route('admin.reports.sales') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.reports.sales') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
        <line x1="6" y1="20" x2="6" y2="14"/>
    </svg>
    <span>Lap. Penjualan</span>
</a>

@if($business->hasFeature('laporan_lengkap'))
<a href="{{ route('admin.reports.products') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.reports.products') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
    </svg>
    <span>Lap. Produk</span>
</a>

<a href="{{ route('admin.reports.customers') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.reports.customers') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
    </svg>
    <span>Lap. Customer</span>
</a>
@endif
 
<p class="px-2 pt-4 pb-1 text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest">Lainnya</p>

@if($business->hasFeature('promo'))
<a href="{{ route('admin.promos.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.promos*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/>
        <line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7z"/>
        <path d="M12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/>
    </svg>
    <span>Set Promo</span>
</a>
@endif

@if($business->hasFeature('customer'))
<a href="{{ route('admin.customers.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.customers*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
    </svg>
    <span>Customer</span>
</a>
@endif

<a href="{{ route('admin.kasirs.index') }}" {{-- selalu tampil, semua paket boleh kelola kasir (sesuai limit) --}}
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.kasirs*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="3"/>
        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
    </svg>
    <span>Kelola Kasir</span>
</a>

<a href="{{ route('admin.subscription.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.subscription*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <i class="bi bi-credit-card text-sm"></i>
    <span>Subscription</span>
</a>
<a href="{{ route('admin.midtrans-setting.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('admin.midtrans-setting*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
        <line x1="1" y1="10" x2="23" y2="10"/>
        <path d="M18 14h.01"/>
        <path d="M14 14h.01"/>
        <path d="M10 14h.01"/>
    </svg>
    <span>Pembayaran</span>
</a>