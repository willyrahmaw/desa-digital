@extends('errors.layout')

@section('title', '500 — Kendala Teknis Peladen')
@section('code', '500')
@section('indicator_color', 'bg-rose-600')
@section('status_badge', 'Peringatan Teknis: Gangguan Internal Peladen')

@section('badge_bg', 'bg-rose-50 border border-rose-200 text-rose-800')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-rose-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
</svg>
@endsection

@section('heading', 'Terjadi Gangguan Teknis Internal pada Peladen Sistem')
@section('message', 'Sistem aplikasi sedang mengalami kendala teknis tak terduga saat mengeksekusi instruksi data yang diminta. Log kesalahan (error log) telah secara otomatis dicatat ke dalam sistem pemantauan pengelola IT desa untuk dianalisis dan ditangani oleh tim teknis.')

@section('suggestions')
<li>Tunggu beberapa saat kemudian muat ulang halaman untuk mencoba memproses kembali permintaan Anda.</li>
<li>Pastikan data yang Anda masukkan pada formulir sebelumnya berformat benar dan valid.</li>
<li>Apabila masalah terjadi berulang, silakan hubungi tim administrasi/IT desa melalui kontak resmi di bawah ini.</li>
@endsection

@section('extra_action')
<button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-rose-800 hover:bg-rose-900 text-white font-semibold text-xs sm:text-sm transition-colors shadow-2xs">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Coba Muat Ulang Halaman
</button>
@endsection
