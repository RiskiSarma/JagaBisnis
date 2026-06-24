@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="space-y-5">

    {{-- Filter tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        @foreach(['pending' => ['Pending','bi-hourglass-split'], 'approved' => ['Disetujui','bi-check-circle'], 'rejected' => ['Ditolak','bi-x-circle'], 'all' => ['Semua','bi-list-ul']] as $key => $info)
        <a href="{{ route('sa.subscriptions.index', ['status' => $key]) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap
           {{ $currentStatus === $key
              ? 'bg-brand text-white shadow-lg shadow-brand/25'
              : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-brand/40' }}">
            <i class="bi {{ $info[1] }}"></i>
            {{ $info[0] }}
        </a>
        @endforeach
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 p-3.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-2 p-3.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Desktop table --}}
    <div class="hidden md:block bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-700/40 text-slate-500 dark:text-slate-400 text-[11px] uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left font-bold">Bisnis</th>
                    <th class="px-5 py-3 text-left font-bold">Paket</th>
                    <th class="px-5 py-3 text-left font-bold">Harga</th>
                    <th class="px-5 py-3 text-left font-bold">Metode</th>
                    <th class="px-5 py-3 text-left font-bold">Bukti</th>
                    <th class="px-5 py-3 text-left font-bold">Status</th>
                    <th class="px-5 py-3 text-left font-bold">Tanggal</th>
                    <th class="px-5 py-3 text-left font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="font-bold text-slate-800 dark:text-white">{{ $sub->business->name }}</p>
                        <p class="text-xs text-slate-400">{{ $sub->user->name }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-1 rounded-lg bg-brand/10 text-brand text-xs font-bold uppercase">{{ $sub->paket }}</span>
                    </td>
                    <td class="px-5 py-3.5 font-semibold text-slate-700 dark:text-slate-200">Rp {{ number_format($sub->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ $sub->payment_method ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        @if($sub->proof_path)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($sub->proof_path) }}" target="_blank"
                           class="inline-flex items-center gap-1 text-brand hover:underline font-medium">
                            <i class="bi bi-image"></i> Lihat
                        </a>
                        @else <span class="text-slate-400">-</span> @endif
                    </td>
                    <td class="px-5 py-3.5">
                        @if($sub->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300"><i class="bi bi-hourglass-split"></i> Pending</span>
                        @elseif($sub->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300"><i class="bi bi-check-lg"></i> Disetujui</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300"><i class="bi bi-x-lg"></i> Ditolak</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-slate-400">{{ $sub->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3.5">
                        @if($sub->status === 'pending')
                        <div class="flex gap-2">
                            <form action="{{ route('sa.subscriptions.approve', $sub) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 text-xs font-bold hover:bg-emerald-200 dark:hover:bg-emerald-500/25 transition flex items-center gap-1">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('sa.subscriptions.reject', $sub) }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300 text-xs font-bold hover:bg-red-200 dark:hover:bg-red-500/25 transition flex items-center gap-1">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-xs text-slate-400">{{ $sub->reviewed_at?->format('d/m/Y H:i') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-14 text-center">
                        <i class="bi bi-inbox text-3xl text-slate-300 dark:text-slate-600"></i>
                        <p class="text-sm text-slate-400 mt-2">Tidak ada data.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden space-y-3">
        @forelse($subscriptions as $sub)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
            <div class="flex items-start justify-between gap-2 mb-2">
                <div>
                    <p class="font-bold text-slate-800 dark:text-white text-sm">{{ $sub->business->name }}</p>
                    <p class="text-xs text-slate-400">{{ $sub->user->name }} · {{ $sub->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($sub->status === 'pending')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300 shrink-0"><i class="bi bi-hourglass-split"></i> Pending</span>
                @elseif($sub->status === 'approved')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 shrink-0"><i class="bi bi-check-lg"></i> Disetujui</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300 shrink-0"><i class="bi bi-x-lg"></i> Ditolak</span>
                @endif
            </div>

            <div class="flex items-center gap-2 text-sm mb-3">
                <span class="px-2 py-1 rounded-lg bg-brand/10 text-brand text-xs font-bold uppercase">{{ $sub->paket }}</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">Rp {{ number_format($sub->price, 0, ',', '.') }}</span>
                <span class="text-slate-400 text-xs">{{ $sub->payment_method ?? '-' }}</span>
            </div>

            @if($sub->proof_path)
            <a href="{{ \Illuminate\Support\Facades\Storage::url($sub->proof_path) }}" target="_blank"
               class="inline-flex items-center gap-1 text-brand text-xs font-medium hover:underline mb-3">
                <i class="bi bi-image"></i> Lihat bukti transfer
            </a>
            @endif

            @if($sub->status === 'pending')
            <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                <form action="{{ route('sa.subscriptions.approve', $sub) }}" method="POST" class="flex-1">
                    @csrf @method('PATCH')
                    <button class="w-full py-2 rounded-lg bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 text-xs font-bold flex items-center justify-center gap-1">
                        <i class="bi bi-check-lg"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('sa.subscriptions.reject', $sub) }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?')" class="flex-1">
                    @csrf @method('PATCH')
                    <button class="w-full py-2 rounded-lg bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300 text-xs font-bold flex items-center justify-center gap-1">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </form>
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-14">
            <i class="bi bi-inbox text-3xl text-slate-300 dark:text-slate-600"></i>
            <p class="text-sm text-slate-400 mt-2">Tidak ada data.</p>
        </div>
        @endforelse
    </div>

    {{ $subscriptions->links() }}
</div>
@endsection