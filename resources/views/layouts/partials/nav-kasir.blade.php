<p class="px-2 pt-3 pb-1 text-[9px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest">Operasional</p>

<a href="{{ route('kasir.pos') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('kasir.pos') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <rect x="4" y="2" width="16" height="20" rx="2"/>
        <line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="10" y2="10"/>
        <line x1="12" y1="10" x2="14" y2="10"/><line x1="8" y1="14" x2="10" y2="14"/>
        <line x1="12" y1="14" x2="14" y2="14"/><line x1="8" y1="18" x2="10" y2="18"/>
    </svg>
    <span>Kasir / POS</span>
</a>

<a href="{{ route('kasir.history') }}" 
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 
   {{ request()->routeIs('kasir.history') 
      ? 'bg-brand/10 dark:bg-brand/40 text-brand' 
      : 'text-slate-600 dark:text-white/45 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-800 dark:hover:text-white' }}">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/>
        <rect x="8" y="2" width="8" height="4" rx="1"/>
        <line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/>
    </svg>
    <span>Riwayat Hari Ini</span>
</a>