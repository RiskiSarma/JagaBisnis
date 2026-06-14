@extends('layouts.app')
@section('title', 'Kelola Kasir')
@section('content')

<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Kelola Kasir</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $kasirs->count() }} kasir</p>
    </div>
    <button type="button" onclick="bukaModalKasir()"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Kasir
    </button>
</div>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Role</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($kasirs as $kasir)
                @php
                    $initials = strtoupper(substr($kasir->name, 0, 1) . (str($kasir->name)->explode(' ')->get(1)[0] ?? ''));
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ $initials }}
                            </div>
                            <span class="font-semibold text-slate-800 dark:text-white">{{ $kasir->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $kasir->email }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                            Kasir
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Aktif
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('admin.kasirs.destroy', $kasir) }}" class="inline" onsubmit="return confirm('Hapus kasir ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center">
                        <div class="inline-flex flex-col items-center gap-3 text-slate-400">
                            <div class="p-4 bg-slate-100 dark:bg-slate-700/50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <p class="text-sm">Belum ada kasir</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Kasir --}}
<div id="kasirOverlay"
     class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this)tutupModalKasir()">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-sm p-6">
        <h3 class="text-base font-extrabold text-slate-800 dark:text-white mb-5">Tambah Kasir</h3>
        <form action="{{ route('admin.kasirs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nama</label>
                <input type="text" name="name" required placeholder="Nama kasir"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" name="email" required placeholder="kasir@toko.com"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password" required placeholder="Password"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 text-sm outline-none focus:border-blue-500 text-slate-700 dark:text-slate-200">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="tutupModalKasir()"
                    class="px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function bukaModalKasir() { document.getElementById('kasirOverlay').classList.remove('hidden'); }
function tutupModalKasir() { document.getElementById('kasirOverlay').classList.add('hidden'); }
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModalKasir();
});
@if($errors->any())
    bukaModalKasir();
@endif
</script>
@endpush

@endsection