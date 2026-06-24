@extends('layouts.app')
@section('title', 'Manajemen Bisnis')
@section('content')
<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Manajemen Bisnis</h2>
        <p class="text-sm text-slate-400 mt-0.5">{{ $businesses->count() }} bisnis terdaftar</p>
    </div>
    <button onclick="document.getElementById('modalTambahBisnis').classList.remove('hidden')"
            class="flex items-center gap-2 bg-[#1A56DB] text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-[#1043b5] transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Bisnis
    </button>
</div>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[800px]">
            <thead><tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                <th class="text-left px-5 py-3">Bisnis</th>
                <th class="text-left px-5 py-3">Tipe</th>
                <th class="text-left px-5 py-3">Manager</th>
                <th class="text-left px-5 py-3">Revenue</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-left px-5 py-3">Fitur Stok</th>
                <th class="text-left px-5 py-3">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @foreach($businesses as $b)
            @php $mgr = $b->users->firstWhere(fn($u) => $u->hasRole('admin')); @endphp
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                <td class="px-5 py-4">
                    <div class="font-semibold text-slate-800 dark:text-white">{{ $b->name }}</div>
                    <div class="text-xs text-slate-400 mt-0.5">{{ $b->total_transactions }} transaksi</div>
                </td>
                <td class="px-5 py-4"><span class="bg-blue-50 text-[#1A56DB] text-xs font-bold px-2 py-0.5 rounded-lg">{{ $b->type }}</span></td>
                <td class="px-5 py-4">
                    @if($mgr)
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#1A56DB] to-blue-400 flex items-center justify-center text-white text-[10px] font-bold">{{ $mgr->initials }}</div>
                        <div><div class="text-xs font-semibold text-slate-800 dark:text-white">{{ $mgr->name }}</div><div class="text-[10px] text-slate-400">{{ $mgr->email }}</div></div>
                    </div>
                    @else<span class="text-xs text-slate-400 italic">Belum ada</span>@endif
                </td>
                <td class="px-5 py-4 font-bold text-slate-800 dark:text-white">Rp {{ number_format($b->total_revenue,0,',','.') }}</td>
                <td class="px-5 py-4">
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $b->status==='active'?'bg-emerald-100 text-emerald-800':'bg-red-100 text-red-700' }}">
                        {{ $b->status==='active'?'Aktif':'Nonaktif' }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <form method="POST" action="{{ route('sa.businesses.toggle-stok', $b) }}" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs {{ $b->feat_stok?'bg-emerald-100 text-emerald-700 hover:bg-red-100 hover:text-red-700':'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 hover:bg-emerald-100 hover:text-emerald-700' }} px-2 py-0.5 rounded-full font-bold transition-colors">
                            {{ $b->feat_stok?'✓ Aktif':'— Off' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        {{-- Ganti form toggle-status yang lama dengan ini --}}
                        @php
                            $hasActiveSub = $b->subscription_status === 'active'
                                && $b->subscription_ends_at
                                && $b->subscription_ends_at->isFuture()
                                && $b->paket !== 'starter';
                        @endphp

                        @if($b->status === 'active' && $hasActiveSub)
                            {{-- Bisnis aktif + punya langganan berbayar aktif: tampilkan warning modal --}}
                            <button type="button"
                                onclick="confirmDeactivate({{ $b->id }}, '{{ addslashes($b->name) }}', '{{ $b->paket }}', '{{ $b->subscription_ends_at->translatedFormat('d M Y') }}')"
                                class="text-xs bg-emerald-500 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg font-bold transition-colors">
                                Aktif
                            </button>
                            {{-- Form hidden untuk submit setelah konfirmasi --}}
                            <form id="toggle-form-{{ $b->id }}" method="POST" action="{{ route('sa.businesses.toggle-status', $b) }}" class="hidden">
                                @csrf @method('PATCH')
                            </form>
                        @else
                            <form method="POST" action="{{ route('sa.businesses.toggle-status', $b) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs {{ $b->status==='active'?'bg-emerald-500 hover:bg-red-500':'bg-slate-300 hover:bg-emerald-500' }} text-white px-3 py-1.5 rounded-lg font-bold transition-colors">
                                    {{ $b->status==='active'?'Aktif':'Off' }}
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('sa.businesses.destroy', $b) }}" onsubmit="return confirm('Hapus bisnis ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs bg-red-50 hover:bg-red-500 text-red-500 hover:text-white px-3 py-1.5 rounded-lg font-bold transition-colors">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Bisnis (sama seperti dashboard) --}}
<div id="modalTambahBisnis" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6">
            <h3 class="text-base font-extrabold mb-4 text-slate-900 dark:text-white">Tambah Bisnis Baru</h3>
            <form method="POST" action="{{ route('sa.businesses.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Bisnis</label>
                        <input name="name" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-[#1A56DB] bg-white dark:bg-slate-700 text-slate-900 dark:text-white"></div>
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipe</label>
                        <select name="type" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-white outline-none">
                            <option>F&B</option><option>Retail</option><option>Laundry</option><option>Jasa</option><option>Lainnya</option>
                        </select></div>
                </div>
                <hr class="border-slate-100 dark:border-slate-700">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Akun Manager</p>
                <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Manager</label>
                    <input name="mgr_name" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-[#1A56DB] bg-white dark:bg-slate-700 text-slate-900 dark:text-white"></div>
                <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Login</label>
                    <input name="mgr_email" type="email" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-[#1A56DB] bg-white dark:bg-slate-700 text-slate-900 dark:text-white"></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1">Password</label>
                        <input name="mgr_password" type="password" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-[#1A56DB] bg-white dark:bg-slate-700 text-slate-900 dark:text-white"></div>
                    <div><label class="block text-xs font-bold text-slate-500 uppercase mb-1">Konfirmasi</label>
                        <input name="mgr_password_confirmation" type="password" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-[#1A56DB] bg-white dark:bg-slate-700 text-slate-900 dark:text-white"></div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalTambahBisnis').classList.add('hidden')"
                            class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#1A56DB] text-white rounded-xl text-sm font-bold hover:bg-[#1043b5]">Buat Bisnis & Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Warning Nonaktifkan Bisnis Berlangganan --}}
<div id="modalDeactivateWarning" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md shadow-2xl p-6">
        <div class="flex items-start gap-4 mb-5">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/15 flex items-center justify-center shrink-0">
                <i class="bi bi-exclamation-triangle-fill text-amber-600 dark:text-amber-400 text-xl"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Nonaktifkan Bisnis Berlangganan?</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Perhatikan informasi berikut sebelum melanjutkan.</p>
            </div>
        </div>

        <div class="bg-amber-50 dark:bg-amber-900/15 border border-amber-200 dark:border-amber-700 rounded-xl p-4 mb-5 space-y-1.5">
            <p class="text-sm font-bold text-amber-800 dark:text-amber-300" id="warningBusinessName"></p>
            <p class="text-sm text-amber-700 dark:text-amber-400" id="warningSubInfo"></p>
            <p class="text-xs text-amber-600 dark:text-amber-500 mt-2">
                ⚠️ Bisnis ini masih memiliki masa langganan berbayar yang aktif. Menonaktifkan secara sepihak dapat menyebabkan keluhan dari pelanggan.
            </p>
        </div>

        <p class="text-sm text-slate-600 dark:text-slate-300 mb-5">
            Apakah Anda yakin ingin menonaktifkan bisnis ini? Pengguna tidak akan dapat mengakses aplikasi meskipun langganan mereka masih berlaku.
        </p>

        <div class="flex gap-3 justify-end">
            <button type="button"
                onclick="document.getElementById('modalDeactivateWarning').classList.add('hidden')"
                class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300">
                Batal
            </button>
            <button type="button" id="btnConfirmDeactivate"
                class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-bold transition-colors">
                Ya, Nonaktifkan
            </button>
        </div>
    </div>
</div>

<script>
function confirmDeactivate(businessId, businessName, paket, endsAt) {
    document.getElementById('warningBusinessName').textContent = '🏢 ' + businessName;
    document.getElementById('warningSubInfo').textContent =
        'Paket ' + paket.toUpperCase() + ' aktif hingga ' + endsAt;

    document.getElementById('btnConfirmDeactivate').onclick = function () {
        document.getElementById('toggle-form-' + businessId).submit();
    };

    document.getElementById('modalDeactivateWarning').classList.remove('hidden');
}
</script>
@endsection