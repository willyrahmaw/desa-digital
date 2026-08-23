@extends('errors.layout')

@section('title', '503 — Pemeliharaan Sistem Rutin')
@section('code', '503')
@section('indicator_color', 'bg-emerald-600')
@section('status_badge', 'Pemberitahuan: Pemeliharaan Layanan Digital')

@section('badge_bg', 'bg-emerald-50 border border-emerald-200 text-emerald-800')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
@endsection

@section('heading', 'Sistem Sedang Dalam Proses Pemeliharaan Rutin')
@section('message', 'Portal layanan digital dan database kependudukan desa saat ini sedang dalam proses peningkatan kapasitas infrastruktur, peremajaan keamanan berkala, dan optimasi performa demi kenyamanan dan kecepatan pelayanan kepada seluruh masyarakat.')

@section('suggestions')
<li>Proses pemeliharaan biasanya hanya memakan waktu beberapa menit. Silakan kunjungi kembali portal sesaat lagi.</li>
<li>Untuk kebutuhan layanan persuratan mendesak saat sistem dalam pemeliharaan, Anda dapat langsung datang ke Kantor Balai Desa pada jam kerja operasional (Senin – Jumat, 08:00 – 15:00 WIB).</li>
<li>Hubungi petugas piket pelayanan desa melalui nomor kontak atau email resmi yang tertera.</li>
@endsection

@section('extra_action')
<button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs sm:text-sm transition-colors shadow-2xs">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    Periksa Status Peladen
</button>
@endsection
