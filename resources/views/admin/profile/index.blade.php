@extends('layouts.admin')

@section('title', 'Profil Pengguna')
@section('breadcrumb-item', 'Profil Saya')
@section('page-title', 'Pengaturan Profil Akun')

@section('content')
<div class="space-y-6 max-w-5xl">

    <!-- User Header Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white font-extrabold text-2xl flex items-center justify-center shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
                    </span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $user->role->name ?? 'Pengguna' }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Bergabung sejak <strong>{{ $user->created_at->format('d M Y') }}</strong></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form 1: Edit Informasi Profil -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <h3 class="text-base font-bold text-slate-900">Informasi Pribadi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama tampilan dan alamat email login akun Anda.</p>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-semibold text-slate-700">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-slate-700">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all">
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-600/20 focus:outline-none transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form 2: Ubah Kata Sandi -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs" x-data="{ showCurr: false, showNew: false }">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <h3 class="text-base font-bold text-slate-900">Perbarui Kata Sandi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang panjang dan aman.</p>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1.5">
                        <label for="current_password" class="block text-xs font-semibold text-slate-700">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showCurr ? 'text' : 'password'" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="block w-full rounded-xl border border-slate-300 px-3.5 pr-10 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all">
                            <button type="button" 
                                    @click="showCurr = !showCurr" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" 
                                   id="password" 
                                   name="password" 
                                   required
                                   autocomplete="new-password"
                                   placeholder="Minimal 6 karakter"
                                   class="block w-full rounded-xl border border-slate-300 px-3.5 pr-10 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all">
                            <button type="button" 
                                    @click="showNew = !showNew" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">
                            Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               autocomplete="new-password"
                               placeholder="Ketik ulang kata sandi baru"
                               class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 bg-white placeholder-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus:ring-4 focus:ring-slate-900/20 focus:outline-none transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Ubah Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sisi Kanan: Ringkasan Hak Akses & Status -->
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Hak Akses & Otoritas</h3>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Peran / Role</span>
                        <span class="font-bold text-slate-800">{{ $user->role->name ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Status Akun</span>
                        <span class="font-semibold text-emerald-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    </div>

                    @if($user->perangkatDesa)
                        <div class="flex justify-between py-1.5 border-b border-slate-50">
                            <span class="text-slate-500">Jabatan Dinas</span>
                            <span class="font-bold text-slate-800">{{ $user->perangkatDesa->jabatan ?? '-' }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">ID Pengguna</span>
                        <span class="font-mono text-slate-600">#{{ $user->id }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-emerald-50/50 p-5 border-emerald-100 text-xs text-emerald-900 space-y-2">
                <div class="flex items-center gap-2 font-bold text-emerald-800">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Keamanan Data Akun</span>
                </div>
                <p class="text-emerald-700/90 leading-relaxed">
                    Setiap aktivitas masuk dan perubahan kredensial akun dicatat secara otomatis ke dalam Audit Log untuk kepatuhan tata kelola digital.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection
