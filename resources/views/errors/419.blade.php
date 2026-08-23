@extends('errors.layout')

@section('title', '419 — Sesi Keamanan Kedaluwarsa')
@section('code', '419')
@section('indicator_color', 'bg-purple-600')
@section('status_badge', 'Pemberitahuan: Sesi Formulir Kedaluwarsa')

@section('badge_bg', 'bg-purple-50 border border-purple-200 text-purple-800')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-purple-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('heading', 'Masa Berlaku Sesi Formulir Keamanan Telah Berakhir')
@section('message', 'Masa aktif sesi atau token keamanan (CSRF Token) formulir yang Anda kirimkan telah berakhir karena halaman didiamkan dalam rentang waktu yang cukup lama. Mekanisme ini diterapkan secara otomatis demi mencegah penyalahgunaan formulir elektronik dan pencurian data.')

@section('suggestions')
<li>Muat ulang (refresh) halaman untuk memperoleh token keamanan sesi yang baru.</li>
<li>Pastikan koneksi internet Anda tetap terhubung saat mengisi formulir permohonan persuratan atau data kependudukan.</li>
<li>Jika Anda sedang dalam proses verifikasi identitas (NIK), lakukan pengisian ulang secara saksama.</li>
@endsection

@section('extra_action')
<button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-purple-800 hover:bg-purple-900 text-white font-semibold text-xs sm:text-sm transition-colors shadow-2xs">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Muat Ulang Halaman (Refresh)
</button>
@endsection
