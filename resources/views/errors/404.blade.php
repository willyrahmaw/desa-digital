@extends('errors.layout')

@section('title', '404 — Halaman Tidak Ditemukan')
@section('code', '404')
@section('indicator_color', 'bg-blue-600')
@section('status_badge', 'Pemberitahuan: Halaman Tidak Ditemukan')

@section('badge_bg', 'bg-blue-50 border border-blue-200 text-blue-800')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('heading', 'Dokumen atau Halaman yang Anda Cari Tidak Ditemukan')
@section('message', 'Tautan naskah, berita publikasi, atau formulir layanan yang Anda tuju tidak dapat ditemukan pada sistem peladen. Kemungkinan tautan telah dipindahkan ke direktori baru, masa berlaku informasi telah selesai, atau terdapat kesalahan penulisan alamat URL pada peramban (browser).')

@section('suggestions')
<li>Periksa kembali penulisan alamat tautan (URL) yang Anda masukkan pada bilah alamat peramban.</li>
<li>Gunakan menu navigasi pada portal utama untuk mencari data, artikel kabar desa, atau formulir administrasi yang relevan.</li>
<li>Apabila Anda mencari layanan administrasi persuratan tertentu, silakan kunjungi menu <strong>Pelayanan Surat</strong>.</li>
<li>Jika kendala berlanjut, laporkan temuan tautan rusak melalui loket <strong>Pengaduan Masyarakat</strong>.</li>
@endsection
