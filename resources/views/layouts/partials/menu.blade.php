{{-- Dashboard --}}
<a href="{{ route('admin.dashboard') }}"
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Dashboard"
   class="nav-link {{ Route::currentRouteName() === 'admin.dashboard' ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
    </svg>
    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
</a>

{{-- ── MASTER DATA ─────────────────────────────────────── --}}
@can('manage-master')
<p x-show="!sidebarCollapsed" class="nav-label">Master Data</p>
<div x-show="sidebarCollapsed" class="my-2 border-t border-slate-800"></div>

<div x-data="{ open: {{ request()->routeIs('admin.master.dusun.*','admin.master.rw.*','admin.master.rt.*','admin.master.perangkat_desa.*','admin.master.parameter.*','admin.master.klasifikasi_surat.*') ? 'true' : 'false' }} }">
    <button @click="sidebarCollapsed ? (toggleSidebar(), open = true) : (open = !open)" 
            type="button" 
            :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between'"
            title="Wilayah & Referensi"
            class="nav-link">
        <span class="flex items-center gap-2.5">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 8.485-7.5 11.9-7.5 11.9s-7.5-3.415-7.5-11.9a7.5 7.5 0 0115 0z"/></svg>
            <span x-show="!sidebarCollapsed" class="truncate">Wilayah & Referensi</span>
        </span>
        <svg x-show="!sidebarCollapsed" class="h-3.5 w-3.5 transition-transform flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open && !sidebarCollapsed" class="pl-5 mt-0.5 space-y-0.5">
        <a href="{{ route('admin.master.dusun.index') }}" class="subnav-link {{ request()->routeIs('admin.master.dusun.*') ? 'active' : '' }}">Dusun</a>
        <a href="{{ route('admin.master.rw.index') }}" class="subnav-link {{ request()->routeIs('admin.master.rw.*') ? 'active' : '' }}">RW</a>
        <a href="{{ route('admin.master.rt.index') }}" class="subnav-link {{ request()->routeIs('admin.master.rt.*') ? 'active' : '' }}">RT</a>
        <a href="{{ route('admin.master.perangkat_desa.index') }}" class="subnav-link {{ request()->routeIs('admin.master.perangkat_desa.*') ? 'active' : '' }}">Perangkat Desa</a>
        <a href="{{ route('admin.master.parameter.index', 'agama') }}" class="subnav-link {{ request()->routeIs('admin.master.parameter.*') ? 'active' : '' }}">Parameter Lookup</a>
        <a href="{{ route('admin.master.klasifikasi_surat.index') }}" class="subnav-link {{ request()->routeIs('admin.master.klasifikasi_surat.*') ? 'active' : '' }}">Klasifikasi Surat</a>
    </div>
</div>
@endcan

{{-- ── KEPENDUDUKAN ─────────────────────────────────────── --}}
@can('manage-penduduk')
<p x-show="!sidebarCollapsed" class="nav-label">Kependudukan</p>
<div x-show="sidebarCollapsed" class="my-2 border-t border-slate-800"></div>

<a href="{{ route('admin.master.penduduk.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Data Penduduk"
   class="nav-link {{ request()->routeIs('admin.master.penduduk.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 20.5a11.382 11.382 0 01-5.542-1.278"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Data Penduduk</span>
</a>
<a href="{{ route('admin.master.kartu_keluarga.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Kartu Keluarga"
   class="nav-link {{ request()->routeIs('admin.master.kartu_keluarga.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Kartu Keluarga</span>
</a>
@endcan

@can('manage-sosial')
<a href="{{ route('admin.master.data_social.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Data Sosial & Bansos"
   class="nav-link {{ request()->routeIs('admin.master.data_social.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.203 0-4.361.186-6.463.545V21h12.926z"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Data Sosial & Bansos</span>
</a>
@endcan

{{-- ── PELAYANAN ─────────────────────────────────────────── --}}
@can('manage-surat')
<p x-show="!sidebarCollapsed" class="nav-label">Pelayanan</p>
<div x-show="sidebarCollapsed" class="my-2 border-t border-slate-800"></div>

<div x-data="{ open: {{ request()->routeIs('admin.master.surat.*','admin.master.template_surat.*') ? 'true' : 'false' }} }">
    <button @click="sidebarCollapsed ? (toggleSidebar(), open = true) : (open = !open)" 
            type="button" 
            :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between'"
            title="Layanan Surat"
            class="nav-link">
        <span class="flex items-center gap-2.5">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            <span x-show="!sidebarCollapsed" class="truncate">Surat</span>
        </span>
        <svg x-show="!sidebarCollapsed" class="h-3.5 w-3.5 transition-transform flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open && !sidebarCollapsed" class="pl-5 mt-0.5 space-y-0.5">
        <a href="{{ route('admin.master.template_surat.index') }}" class="subnav-link {{ request()->routeIs('admin.master.template_surat.*') ? 'active' : '' }}">Template Surat</a>
        <a href="{{ route('admin.master.surat.index') }}" class="subnav-link {{ request()->routeIs('admin.master.surat.*') ? 'active' : '' }}">Daftar Pengajuan</a>
    </div>
</div>
@endcan

@can('manage-pengaduan')
<a href="{{ route('admin.master.pengaduan.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Pengaduan Warga"
   class="nav-link {{ request()->routeIs('admin.master.pengaduan.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 18.97a5.969 5.969 0 01-.749-2.553C3.584 15.003 3 13.57 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Pengaduan Warga</span>
</a>
@endcan

{{-- ── PUBLIKASI ─────────────────────────────────────────── --}}
@canany(['manage-berita','manage-agenda','manage-galeri'])
<p x-show="!sidebarCollapsed" class="nav-label">Publikasi</p>
<div x-show="sidebarCollapsed" class="my-2 border-t border-slate-800"></div>

@can('manage-berita')
<a href="{{ route('admin.master.berita.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Berita & Artikel"
   class="nav-link {{ request()->routeIs('admin.master.berita.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Berita & Artikel</span>
</a>
<a href="{{ route('admin.master.kategori_berita.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Kategori Berita"
   class="nav-link {{ request()->routeIs('admin.master.kategori_berita.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Kategori Berita</span>
</a>
@endcan
@can('manage-agenda')
<a href="{{ route('admin.master.agenda.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Agenda Desa"
   class="nav-link {{ request()->routeIs('admin.master.agenda.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Agenda Desa</span>
</a>
@endcan
@can('manage-galeri')
<a href="{{ route('admin.master.galeri.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Galeri Desa"
   class="nav-link {{ request()->routeIs('admin.master.galeri.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Galeri Desa</span>
</a>
<a href="{{ route('admin.master.banner_hero.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Banner Hero Slider"
   class="nav-link {{ request()->routeIs('admin.master.banner_hero.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Banner Hero Slider</span>
</a>
@endcan
@endcanany

{{-- ── EKONOMI DESA ──────────────────────────────────────── --}}
@canany(['manage-umkm','manage-bumdes','manage-apbdes'])
<p x-show="!sidebarCollapsed" class="nav-label">Ekonomi Desa</p>
<div x-show="sidebarCollapsed" class="my-2 border-t border-slate-800"></div>

@canany(['manage-umkm','manage-bumdes'])
<div x-data="{ open: {{ request()->routeIs('admin.master.umkm.*','admin.master.bumdes.*') ? 'true' : 'false' }} }">
    <button @click="sidebarCollapsed ? (toggleSidebar(), open = true) : (open = !open)" 
            type="button" 
            :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between'"
            title="UMKM & BUMDes"
            class="nav-link">
        <span class="flex items-center gap-2.5">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-3.658 3.001 3.001 0 006 0 3.001 3.001 0 003.75 3.658m-13.5 0h13.5"/></svg>
            <span x-show="!sidebarCollapsed" class="truncate">UMKM & BUMDes</span>
        </span>
        <svg x-show="!sidebarCollapsed" class="h-3.5 w-3.5 transition-transform flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open && !sidebarCollapsed" class="pl-5 mt-0.5 space-y-0.5">
        @can('manage-umkm')
        <a href="{{ route('admin.master.umkm.index') }}" class="subnav-link {{ request()->routeIs('admin.master.umkm.*') ? 'active' : '' }}">UMKM</a>
        @endcan
        @can('manage-bumdes')
        <a href="{{ route('admin.master.bumdes.index') }}" class="subnav-link {{ request()->routeIs('admin.master.bumdes.*') ? 'active' : '' }}">BUMDes</a>
        @endcan
    </div>
</div>
@endcanany
@can('manage-apbdes')
<a href="{{ route('admin.master.apbdes.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="APBDes (Anggaran Desa)"
   class="nav-link {{ request()->routeIs('admin.master.apbdes.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5M5.25 7.5h13.5m-12 3h10.5m-9 3h7.5"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">APBDes (Anggaran)</span>
</a>
@endcan
@endcanany

{{-- ── ADMINISTRASI ──────────────────────────────────────── --}}
@canany(['manage-users','view-logs','manage-settings'])
<p x-show="!sidebarCollapsed" class="nav-label">Administrasi</p>
<div x-show="sidebarCollapsed" class="my-2 border-t border-slate-800"></div>

@can('manage-users')
<a href="{{ route('admin.user.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Manajemen User"
   class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Manajemen User</span>
</a>
@endcan
@can('view-logs')
<a href="{{ route('admin.audit.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
   title="Audit Trail"
   class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.375m0-10.5h1.125a2.25 2.25 0 012.25 2.25v11.25a2.25 2.25 0 01-2.25 2.25H5.625a2.25 2.25 0 01-2.25-2.25V6.75a2.25 2.25 0 012.25-2.25H6.75m3 0h3.75"/></svg>
    <span x-show="!sidebarCollapsed" class="truncate">Audit Trail</span>
</a>
@endcan
@can('manage-settings')
<div x-data="{ open: {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }">
    <button @click="sidebarCollapsed ? (toggleSidebar(), open = true) : (open = !open)" 
            type="button" 
            :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between'"
            title="Pengaturan Sistem"
            class="nav-link">
        <span class="flex items-center gap-2.5">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.397-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span x-show="!sidebarCollapsed" class="truncate">Pengaturan</span>
        </span>
        <svg x-show="!sidebarCollapsed" class="h-3.5 w-3.5 transition-transform flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    </button>
    <div x-show="open && !sidebarCollapsed" class="pl-5 mt-0.5 space-y-0.5">
        <a href="{{ route('admin.settings.index') }}" class="subnav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">Pengaturan Umum</a>
        <a href="{{ route('admin.settings.penomoran.index') }}" class="subnav-link {{ request()->routeIs('admin.settings.penomoran.*') ? 'active' : '' }}">Penomoran Surat</a>
    </div>
</div>
@endcan
@endcanany
