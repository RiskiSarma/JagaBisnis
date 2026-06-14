@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')

{{-- Page Header --}}
<div class="flex flex-wrap items-start justify-between gap-3 mb-5">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Semua Pengguna</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            {{ $users->count() }} pengguna terdaftar (tidak termasuk super admin)
        </p>
    </div>
    <button onclick="document.getElementById('modal-add-manager').classList.remove('hidden')"
        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand-dark transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Manager
    </button>
</div>

{{-- Filter tabs --}}
<div class="flex flex-wrap gap-2 mb-5">
    <a href="{{ route('sa.users.index') }}"
        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
            {{ !request('biz') ? 'bg-brand text-white' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-brand hover:text-brand' }}">
        Semua ({{ $users->count() }})
    </a>
    @foreach($businesses as $biz)
    <a href="{{ route('sa.users.index', ['biz' => $biz->id]) }}"
        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
            {{ request('biz') == $biz->id ? 'bg-brand text-white' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-brand hover:text-brand' }}">
        {{ $biz->name }} ({{ $biz->users->count() }})
    </a>
    @endforeach
</div>

{{-- Table --}}
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" style="min-width:580px">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-[10px] font-bold uppercase tracking-[0.05em] text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Bisnis</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                @forelse($users as $user)
                @php $biz = $businesses->firstWhere('id', $user->business_id); @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">

                    {{-- Nama --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-brand to-blue-400 flex items-center justify-center text-[11px] font-bold text-white shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' '), 1, 1)) }}
                            </div>
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $user->name }}</span>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $user->email }}</td>

                    {{-- Role --}}
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                            {{ $user->hasRole('admin')
                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
                                : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                            {{ $user->hasRole('admin') ? 'Manager' : 'Kasir' }}
                        </span>
                    </td>

                    {{-- Bisnis --}}
                    <td class="px-4 py-3">
                        @if($biz)
                            <span class="inline-flex items-center px-2 py-0.5 bg-brand/10 text-brand rounded-md text-[11px] font-semibold">
                                {{ $biz->name }}
                            </span>
                        @else
                            <span class="text-slate-400 dark:text-slate-500 text-xs">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                            {{ $biz && $biz->status === 'active'
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400'
                                : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                            {{ $biz && $biz->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            <button onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->hasRole('admin') ? 'admin' : 'kasir' }}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-lg hover:border-brand hover:text-brand transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </button>
                            <form action="{{ route('sa.users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-500 text-white text-xs font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6M9 6V4h6v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-16 text-center">
                        <div class="inline-flex flex-col items-center gap-3 text-slate-400 dark:text-slate-500">
                            <div class="p-4 bg-slate-100 dark:bg-slate-700/50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                            </div>
                            <p class="text-sm">Tidak ada pengguna</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ MODAL: Tambah Manager ══ --}}
<div id="modal-add-manager" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <form action="{{ route('sa.users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100">Tambah Manager Bisnis</h3>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Bisnis</label>
                <select name="business_id" required
                    class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
                    @foreach($businesses as $biz)
                    <option value="{{ $biz->id }}">{{ $biz->name }} ({{ $biz->type }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama manager"
                    class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Email Login</label>
                <input type="email" name="email" required placeholder="email@toko.com"
                    class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Password</label>
                    <input type="password" name="password" required placeholder="Min. 6 karakter"
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Konfirmasi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
                </div>
            </div>

            <input type="hidden" name="role" value="admin">

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-add-manager').classList.add('hidden')"
                    class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 text-sm font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand-dark transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: Edit User ══ --}}
<div id="modal-edit-user" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <form id="form-edit-user" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100">Edit Pengguna</h3>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                <input type="text" id="edit-name" name="name" required
                    class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Email Login</label>
                <input type="email" id="edit-email" name="email" required
                    class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Role</label>
                <select id="edit-role" name="role"
                    class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
                    <option value="admin">Manager / Admin</option>
                    <option value="kasir">Kasir</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1">
                    Ganti Password
                    <span class="normal-case font-normal text-slate-400">(kosongkan jika tidak diubah)</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <input type="password" name="password" placeholder="Password baru"
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi"
                        class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-edit-user').classList.add('hidden')"
                    class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 text-sm font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand-dark transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(id, name, email, role) {
    document.getElementById('edit-name').value  = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-role').value  = role;
    document.getElementById('form-edit-user').action = '/sa/users/' + id;
    document.getElementById('modal-edit-user').classList.remove('hidden');
}
['modal-add-manager', 'modal-edit-user'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endpush

@endsection