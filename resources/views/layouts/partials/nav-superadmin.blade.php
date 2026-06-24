@php
$navSection = fn($label) => "<p class='px-2 pt-3 pb-1 text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest'>$label</p>";
@endphp
 
{!! $navSection('Platform') !!}

<a href="{{ route('sa.dashboard') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('sa.dashboard') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <i class="bi bi-grid"></i> 
    <span>Dashboard Global</span>
</a>

<a href="{{ route('sa.businesses.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('sa.businesses*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <i class="bi bi-building"></i> 
    <span>Manajemen Bisnis</span>
</a>

<a href="{{ route('sa.features') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('sa.features') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <i class="bi bi-sliders"></i> 
    <span>Fitur Bisnis</span>
</a>

<a href="{{ route('sa.users.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('sa.users*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <i class="bi bi-people"></i> 
    <span>Pengguna</span>
</a>

<a href="{{ route('sa.monitor') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('sa.monitor') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <i class="bi bi-binoculars"></i> 
    <span>Monitoring</span>
</a>
@php
    $pendingSubCount = \App\Models\Subscription::where('status', 'pending')->count();
@endphp

<a href="{{ route('sa.subscriptions.index') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('sa.subscriptions*') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <i class="bi bi-credit-card"></i>
    <span>Konfirmasi Pembayaran</span>
    @if($pendingSubCount > 0)
        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $pendingSubCount }}</span>
    @endif
</a>
