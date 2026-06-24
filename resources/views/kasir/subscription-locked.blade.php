@extends('layouts.app')

@section('title', 'Akses Terkunci')

@section('content')
<div class="max-w-lg mx-auto mt-12 text-center">
    <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-500/15 flex items-center justify-center mx-auto mb-5">
        <i class="bi bi-lock-fill text-red-600 dark:text-red-400 text-2xl"></i>
    </div>

    <h2 class="font-bold text-slate-800 dark:text-white text-lg mb-2">Akses Bisnis Sedang Nonaktif</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
        Masa uji coba atau langganan bisnis <span class="font-semibold">{{ auth()->user()->business->name }}</span>
        sudah berakhir. Silakan hubungi pemilik atau admin toko untuk memperbarui paket langganan sebelum melanjutkan transaksi.
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </button>
    </form>
</div>
@endsection