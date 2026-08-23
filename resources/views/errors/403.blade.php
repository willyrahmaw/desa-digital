@extends('errors.layout')

@section('title', '403 — Akses Dibatasi & Ditolak')
@section('code', '403')
@section('indicator_color', 'bg-amber-600')
@section('status_badge', 'Peringatan Keamanan: Hak Akses Terbatas')

@section('badge_bg', 'bg-amber-50 border border-amber-200 text-amber-800')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
@endsection

@section('heading', 'Akses Menu Ini Dibatasi untuk Pengguna Tertentu')
@section('message', 'Sistem mendeteksi bahwa akun Anda saat ini tidak memiliki wewenang atau hak akses operasional (role/permission) untuk membuka halaman administrasi ini. Hal ini bertujuan untuk menjaga kerahasiaan data kependudukan dan integritas arsip pemerintahan desa.')

@section('suggestions')
<li>Pastikan Anda telah masuk (login) menggunakan akun resmi perangkat atau operator desa yang memiliki wewenang terkait.</li>
<li>Jika Anda adalah perangkat desa yang membutuhkan akses modul ini, hubungi Administrator Sistem Desa (Sekretaris / Operator Utama).</li>
<li>Kembali ke dasbor utama untuk mengakses modul-modul yang telah didelegasikan kepada akun Anda.</li>
@endsection

@section('extra_action')
<a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-amber-700 hover:bg-amber-800 text-white font-semibold text-xs sm:text-sm transition-colors shadow-2xs">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
    Masuk ke Akun Lain
</a>
@endsection
