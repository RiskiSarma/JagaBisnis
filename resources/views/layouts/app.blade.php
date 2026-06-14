<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JagaBisnis POS')</title>
    
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png">
    {{-- Atau bisa juga pakai format ico --}}
    {{-- <link rel="icon" type="image/x-icon" href="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png"> --}}
    
    {{-- Untuk Apple devices (iPhone, iPad) --}}
    <link rel="apple-touch-icon" href="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Critical script untuk theme --}}
    <script>
        (function() {
            try {
                // Default: LIGHT MODE (hapus class dark)
                const isDark = localStorage.getItem('jb_dark') === '1';
                
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    // Set default jika belum ada
                    if (localStorage.getItem('jb_dark') === null) {
                        localStorage.setItem('jb_dark', '0');
                    }
                }
                
                console.log('Initial theme:', isDark ? 'dark' : 'light');
            } catch(e) {
                console.error('Theme initialization error:', e);
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { 
            display: none !important; 
        }
        
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        
        /* Perbaikan kontras untuk sidebar navigation links */
        .nav-link {
            @apply flex items-center gap-2.5 px-3 py-2 text-sm font-medium rounded-lg transition-all;
        }
        
        .nav-link-light {
            @apply text-slate-700 hover:bg-slate-100 hover:text-slate-900;
        }
        
        .nav-link-dark {
            @apply text-slate-300 hover:bg-white/10 hover:text-white;
        }
        
        .nav-link-active-light {
            @apply bg-brand/10 text-brand font-semibold;
        }
        
        .nav-link-active-dark {
            @apply bg-brand/20 text-brand font-semibold;
        }
    </style>
</head>

<body x-data="appShell()" x-init="initTheme()" x-cloak 
      class="bg-slate-50 dark:bg-slate-900 font-sans text-slate-900 dark:text-slate-100 h-screen overflow-hidden">

<div class="flex h-screen">

    {{-- ── SIDEBAR dengan kontras yang lebih baik ── --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:static inset-y-0 left-0 z-30 w-64 flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-all duration-300 shrink-0 shadow-lg dark:shadow-2xl">
        {{-- 
            Light mode: bg-white, border-slate-200, shadow-lg
            Dark mode: bg-slate-900, border-slate-800, shadow-2xl
        --}}

        {{-- Brand --}}
        <div class="flex items-center gap-2.5 px-4 h-[60px] border-b border-slate-200 dark:border-slate-800 shrink-0">
            <img src="https://res.cloudinary.com/dx21r1pko/image/upload/q_auto/f_auto/v1776681943/logo_jagabisnis_usq1pu.png"
                 alt="JagaBisnis" class="h-8 object-contain">
            <span class="font-extrabold text-sm tracking-tight text-slate-800 dark:text-white">JagaBisnis</span>
        </div>

        {{-- Role badge dengan kontras baik --}}
        <div class="mx-3 mt-3 mb-1 px-2.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5
            @auth
                @if(auth()->user()->hasRole('superadmin')) 
                    bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30
                @elseif(auth()->user()->hasRole('admin'))  
                    bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30
                @else 
                    bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/30 
                @endif
            @endauth">
            @auth
                @if(auth()->user()->hasRole('superadmin')) ⚡ Super Admin
                @elseif(auth()->user()->hasRole('admin'))  🏪 Manager
                @else 🧾 Kasir @endif
            @endauth
        </div>

        {{-- Nav dengan dynamic classes untuk kontras --}}
        <nav class="flex-1 overflow-y-auto py-2 space-y-0.5 px-2">
            @auth
                @if(auth()->user()->hasRole('superadmin'))
                    @include('layouts.partials.nav-superadmin')
                @elseif(auth()->user()->hasRole('admin'))
                    @include('layouts.partials.nav-admin')
                @else
                    @include('layouts.partials.nav-kasir')
                @endif
            @endauth
        </nav>

        {{-- User footer dengan kontras baik --}}
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand to-blue-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ auth()->user()->initials ?? strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold truncate text-slate-800 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] truncate text-slate-500 dark:text-slate-400">{{ auth()->user()->business?->name ?? 'Platform' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="p-1.5 rounded-md bg-red-100 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-500/30 transition-all"
                        title="Keluar">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen=false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-transition.opacity></div>

    {{-- ── MAIN ── --}}
    <div class="flex flex-col flex-1 overflow-hidden min-w-0">

        {{-- ── TOPBAR ── --}}
        <header class="h-[60px] bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3 px-4 shrink-0 relative">

            {{-- Hamburger --}}
            <button @click="sidebarOpen=!sidebarOpen"
                class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:border-brand dark:hover:border-brand transition-all">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="text-base font-bold text-slate-800 dark:text-white truncate">@yield('title', 'Dashboard')</h1>

            {{-- Right section --}}
            <div class="ml-auto flex items-center gap-2">

                {{-- ── DARK MODE TOGGLE ── --}}
                <button type="button" @click="toggleTheme()"
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:border-brand dark:hover:border-brand transition-all"
                    :title="isDarkMode ? 'Mode Terang' : 'Mode Gelap'">
                    
                    <svg x-show="!isDarkMode" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    
                    <svg x-show="isDarkMode" class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-width="2"
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                    </svg>
                </button>

                {{-- ── NOTIFICATION BELL ── --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                        class="relative w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:border-brand dark:hover:border-brand transition-all"
                        title="Notifikasi">
                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    {{-- Notification dropdown --}}
                    <div x-show="open"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute right-0 top-full mt-2 w-72 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg z-50 overflow-hidden">

                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-sm font-bold text-slate-700 dark:text-white">Notifikasi</span>
                            <button type="button" @click="open=false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-lg leading-none">&times;</button>
                        </div>

                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                            @auth
                            @php
                                $notifs = [];

                                if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin')) {
                                    $lowStockProds = \App\Models\Product::where('business_id', auth()->user()->business_id)
                                        ->where('stock', '<', 10)->get();
                                    foreach($lowStockProds as $lp) {
                                        $notifs[] = ['type'=>'warn','icon'=>'⚠️','title'=>'Stok Menipis','msg'=> $lp->name.' tersisa '.$lp->stock.' pcs'];
                                    }

                                    $belumCount = \App\Models\Transaction::where('business_id', auth()->user()->business_id)
                                        ->where('status','!=','lunas')->count();
                                    if($belumCount > 0) {
                                        $notifs[] = ['type'=>'danger','icon'=>'🔴','title'=>'Transaksi Belum Lunas','msg'=> $belumCount.' transaksi menunggu pelunasan'];
                                    }

                                    $promoCount = \App\Models\Promo::where('business_id', auth()->user()->business_id)
                                        ->where('status','active')->count();
                                    if($promoCount > 0) {
                                        $notifs[] = ['type'=>'info','icon'=>'🎁','title'=>'Promo Aktif','msg'=> $promoCount.' promo sedang berjalan'];
                                    }
                                }

                                if(auth()->user()->hasRole('kasir')) {
                                    $todayKasirTx = \App\Models\Transaction::where('business_id', auth()->user()->business_id)
                                        ->where('user_id', auth()->id())
                                        ->whereDate('created_at', today())
                                        ->get();
                                    $belumKasir = $todayKasirTx->where('status','!=','lunas')->count();
                                    if($belumKasir > 0) {
                                        $notifs[] = ['type'=>'warn','icon'=>'🔴','title'=>'Belum Lunas','msg'=> $belumKasir.' transaksi Anda belum lunas hari ini'];
                                    }
                                    if($todayKasirTx->count() > 0) {
                                        $notifs[] = ['type'=>'info','icon'=>'✅','title'=>'Shift Hari Ini','msg'=> $todayKasirTx->count().' transaksi · Total Rp '.number_format($todayKasirTx->sum('total'),0,',','.')];
                                    }
                                }

                                if(empty($notifs)) {
                                    $notifs[] = ['type'=>'ok','icon'=>'✅','title'=>'Semua Baik','msg'=>'Tidak ada notifikasi baru'];
                                }
                            @endphp

                            @foreach($notifs as $notif)
                            <div class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                                <span class="text-lg leading-none mt-0.5 shrink-0">{{ $notif['icon'] }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-700 dark:text-white">{{ $notif['title'] }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $notif['msg'] }}</p>
                                </div>
                            </div>
                            @endforeach
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand to-blue-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ auth()->user()->initials ?? strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success') || session('error'))
        <div class="px-5 pt-4">
            @if(session('success'))
            <div class="flex items-center gap-2 p-3 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium mb-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-2 p-3 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto @yield('content-class', 'p-5')">
            @yield('content')
        </main>
    </div>
</div>

@livewireScripts
@stack('scripts')

<script>
function appShell() {
    return {
        sidebarOpen: false,
        isDarkMode: false,
        
        initTheme() {
            const savedTheme = localStorage.getItem('jb_dark');
            
            if (savedTheme === '1') {
                this.isDarkMode = true;
            } else {
                this.isDarkMode = false;
                if (savedTheme === null) {
                    localStorage.setItem('jb_dark', '0');
                }
            }
            
            this.applyTheme();
            console.log('Theme initialized:', this.isDarkMode ? 'dark' : 'light');
        },
        
        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            localStorage.setItem('jb_dark', this.isDarkMode ? '1' : '0');
            this.applyTheme();
            console.log('Theme toggled to:', this.isDarkMode ? 'dark' : 'light');
        },
        
        applyTheme() {
            const htmlElement = document.documentElement;
            if (this.isDarkMode) {
                htmlElement.classList.add('dark');
            } else {
                htmlElement.classList.remove('dark');
            }
        }
    }
}
</script>
</body>
</html>