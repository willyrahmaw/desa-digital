@php
    try {
        $appSettings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();
        $namaDesa = $appSettings['nama_desa'] ?? 'Desa Candraloka';
    } catch(\Exception $e) {
        $appSettings = [];
        $namaDesa = 'Desa Candraloka';
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — E-Desa</title>

    @if(!empty($appSettings['favicon']))
        <link rel="icon" href="{{ asset('storage/' . $appSettings['favicon']) }}">
    @elseif(!empty($appSettings['logo_desa']))
        <link rel="icon" href="{{ asset('storage/' . $appSettings['logo_desa']) }}">
    @endif

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- jQuery & DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Select2 Searchable Dropdown CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ── Select2 Searchable Dropdown Modern Tailwind Theme ── */
        .select2-container {
            width: 100% !important;
            font-family: inherit !important;
        }
        .select2-container--default .select2-selection--single {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.375rem !important;
            height: 38px !important;
            padding: 0.25rem 0.5rem !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
            font-size: 0.875rem !important;
            line-height: normal !important;
            padding-left: 0.25rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 8px !important;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1) !important;
            background-color: #ffffff !important;
            z-index: 99999 !important;
            overflow: hidden !important;
        }
        .select2-search--dropdown {
            padding: 0.5rem !important;
            background-color: #f8fafc !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.375rem !important;
            padding: 0.4rem 0.65rem !important;
            font-size: 0.8125rem !important;
            width: 100% !important;
            outline: none !important;
            background-color: #ffffff !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 1px #4f46e5 !important;
        }
        .select2-results__option {
            padding: 0.45rem 0.75rem !important;
            font-size: 0.8125rem !important;
            color: #1e293b !important;
            cursor: pointer !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #eef2ff !important;
            color: #4338ca !important;
            font-weight: 600 !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
        }
    </style>

    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
        .nav-label { font-size: 10px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #475569; padding: 12px 12px 4px; }
        .nav-link  { display: flex; align-items: center; gap: 9px; padding: 7px 10px; border-radius: 6px; font-size: 13px; font-weight: 500; color: #94a3b8; transition: background .12s, color .12s; text-decoration: none; width: 100%; border: none; background: transparent; cursor: pointer; }
        .nav-link:hover   { background: #1e293b; color: #f1f5f9; }
        .nav-link.active  { background: #1e293b; color: #fff; }
        .nav-link svg     { width: 15px; height: 15px; flex-shrink: 0; }
        .subnav-link { display: block; padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: 500; color: #64748b; transition: background .12s, color .12s; text-decoration: none; }
        .subnav-link:hover  { background: #1e293b; color: #e2e8f0; }
        .subnav-link.active { background: #1e293b; color: #fff; }

        /* ── DataTables Modern & Correct Styling ── */
        .dataTables_wrapper {
            position: relative;
            clear: both;
            padding: 1rem 1.25rem !important;
            font-size: 0.8125rem !important;
            color: #334155 !important;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 0.85rem !important;
        }
        .dataTables_wrapper .dataTables_length {
            float: left !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: #64748b !important;
        }
        .dataTables_wrapper .dataTables_length select {
            padding: 0.35rem 1.75rem 0.35rem 0.65rem !important;
            border-radius: 0.375rem !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            background-color: #ffffff !important;
            outline: none !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            margin: 0 0.35rem !important;
            cursor: pointer !important;
        }
        .dataTables_wrapper .dataTables_filter {
            float: right !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: #64748b !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.35rem 0.75rem !important;
            border-radius: 0.375rem !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.75rem !important;
            color: #0f172a !important;
            outline: none !important;
            margin-left: 0.4rem !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            transition: all 0.15s ease-in-out !important;
            width: 200px !important;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15) !important;
        }
        .dataTables_wrapper .dataTables_info {
            float: left !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            color: #64748b !important;
            padding-top: 0.75rem !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            float: right !important;
            padding-top: 0.75rem !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-block !important;
            padding: 0.3rem 0.65rem !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            border-radius: 0.375rem !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #475569 !important;
            margin-left: 0.25rem !important;
            cursor: pointer !important;
            transition: all 0.15s ease-in-out !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.03) !important;
            text-decoration: none !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #4338ca !important;
            color: #ffffff !important;
            border-color: #4338ca !important;
            box-shadow: 0 2px 4px 0 rgba(67, 56, 202, 0.25) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
            background: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
        }
        table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
            border-top: 1px solid #f1f5f9 !important;
            clear: both !important;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0 !important;
        }
        table.dataTable thead th {
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.725rem !important;
            font-weight: 700 !important;
            color: #475569 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            background-color: #f8fafc !important;
        }
        table.dataTable tbody tr {
            transition: background-color 0.12s ease-in-out !important;
            background-color: #ffffff !important;
        }
        table.dataTable tbody tr:hover {
            background-color: #f8fafc !important;
        }
        table.dataTable tbody td {
            padding: 0.75rem 1rem !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        /* ── Modern App Layout Dimensions ── */
        .app-layout-wrapper {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            width: 100%;
            background-color: #f8fafc;
            position: relative;
        }
        .app-sidebar {
            display: none;
            flex-direction: column;
            background-color: #0f172a;
            border-right: 1px solid #1e293b;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 30;
            transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            width: 15rem;
            min-width: 15rem;
            max-width: 15rem;
        }
        @media (min-width: 1024px) {
            .app-sidebar {
                display: flex !important;
            }
        }
        .app-sidebar.is-collapsed {
            width: 4.5rem !important;
            min-width: 4.5rem !important;
            max-width: 4.5rem !important;
        }
        .app-main-panel {
            display: flex;
            flex-direction: column;
            flex: 1 1 0%;
            min-width: 0;
            max-width: 100%;
            background-color: #f8fafc;
        }
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-slate-50 text-slate-800" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
          toggleSidebar() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('sidebar_collapsed', this.sidebarCollapsed);
          }
      }">

{{-- ── MOBILE OVERLAY ──────────────────────────────────── --}}
<div x-show="sidebarOpen" class="fixed inset-0 z-50 lg:hidden flex" style="display:none;">
    <div class="absolute inset-0 bg-slate-900/60" @click="sidebarOpen = false"></div>
    <div class="relative z-10 flex flex-col w-60 bg-slate-900 overflow-y-auto">
        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-4 py-3.5 border-b border-slate-800">
            @if(!empty($appSettings['logo_desa']))
                <img src="{{ asset('storage/' . $appSettings['logo_desa']) }}" alt="Logo" class="w-8 h-8 object-contain flex-shrink-0">
            @else
                <div class="w-8 h-8 bg-indigo-600 rounded flex items-center justify-center text-white text-xs font-black flex-shrink-0">ED</div>
            @endif
            <span class="text-white font-bold text-sm">E-Desa</span>
            <button @click="sidebarOpen = false" class="ml-auto text-slate-500 hover:text-white cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 px-2 py-2">
            @include('layouts.partials.menu')
        </nav>
    </div>
</div>

<!-- ── MAIN APPLICATION SHELL (FLEX ROW: IMPOSSIBLE TO STACK OR OVERLAP) ── -->
<div class="app-layout-wrapper">

    {{-- ── DESKTOP SIDEBAR ───────── --}}
    <aside class="app-sidebar sidebar-scroll"
           :class="{ 'is-collapsed': sidebarCollapsed }"
           :style="sidebarCollapsed ? 'width: 4.5rem !important; min-width: 4.5rem !important; max-width: 4.5rem !important;' : 'width: 15rem !important; min-width: 15rem !important; max-width: 15rem !important;'">
        
        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-3.5 py-3.5 border-b border-slate-800 overflow-hidden shrink-0"
             :class="sidebarCollapsed ? 'justify-center px-0' : ''">
            @if(!empty($appSettings['logo_desa']))
                <img src="{{ asset('storage/' . $appSettings['logo_desa']) }}" alt="Logo" class="w-8 h-8 object-contain flex-shrink-0">
            @else
                <div class="w-8 h-8 bg-indigo-600 rounded flex items-center justify-center text-white text-xs font-black flex-shrink-0">ED</div>
            @endif
            <div x-show="!sidebarCollapsed" class="min-w-0 flex-1">
                <p class="text-white font-bold text-sm leading-none truncate">E-Desa</p>
                <p class="text-slate-500 text-xs mt-0.5 leading-none truncate">Admin Panel</p>
            </div>
        </div>

        {{-- Nama Desa --}}
        <div x-show="!sidebarCollapsed" class="px-4 py-2.5 border-b border-slate-800 overflow-hidden shrink-0">
            <p class="text-slate-500 text-xs">Wilayah</p>
            <p class="text-slate-200 text-xs font-semibold mt-0.5 truncate">{{ $namaDesa }}</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-2">
            @include('layouts.partials.menu')
        </nav>

        {{-- Bottom Collapse Button --}}
        <div class="border-t border-slate-800 p-2 shrink-0">
            <button type="button"
                    @click="toggleSidebar()"
                    class="w-full flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer"
                    :class="sidebarCollapsed ? 'justify-center' : 'justify-between'"
                    :title="sidebarCollapsed ? 'Buka Sidebar Penuh' : 'Ciutkan Sidebar (Perluas Layar)'">
                <span x-show="!sidebarCollapsed" class="text-[11px] font-medium">Ciutkan Menu</span>
                <svg class="w-4 h-4 transition-transform duration-300 flex-shrink-0" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/>
                </svg>
            </button>
        </div>

        {{-- User info --}}
        <div class="border-t border-slate-800 px-3.5 py-3 flex items-center gap-2.5 overflow-hidden shrink-0"
             :class="sidebarCollapsed ? 'justify-center px-0' : ''">
            <div class="w-7 h-7 bg-indigo-600 rounded flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                 :title="sidebarCollapsed ? '{{ auth()->user()->name ?? 'User' }} ({{ auth()->user()->role->label ?? 'Admin' }})' : ''">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div x-show="!sidebarCollapsed" class="min-w-0 flex-1">
                <p class="text-slate-200 text-xs font-semibold truncate">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-slate-500 text-xs truncate">{{ auth()->user()->role->label ?? 'Admin' }}</p>
            </div>
        </div>
    </aside>

    {{-- ── MAIN CONTENT (FLEX-1: SITS ADJACENT, ZERO OVERLAP RISK) ────────── --}}
    <div class="app-main-panel flex-1 flex flex-col min-w-0 max-w-full bg-slate-50">

        {{-- TOPBAR --}}
        <div class="sticky top-0 z-30 flex h-14 items-center bg-white border-b border-slate-200 px-4 sm:px-6 gap-3 shrink-0 shadow-2xs">
            {{-- Mobile Hamburger --}}
            <button type="button" @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 -ml-1 p-1 rounded cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>

            {{-- Desktop Sidebar Toggle (Buka/Tutup Lebar) --}}
            <button type="button" 
                    @click="toggleSidebar()" 
                    class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer -ml-2 mr-1"
                    :title="sidebarCollapsed ? 'Buka Sidebar (Lebar)' : 'Ciutkan Sidebar (Perluas Layar)'">
                <svg class="h-5 w-5 transition-transform duration-200" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <nav class="hidden sm:flex items-center gap-1.5 text-xs flex-1">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-slate-600 font-medium">E-Desa</a>
                <svg class="h-3 w-3 text-slate-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                <span class="text-slate-600 font-semibold">@yield('breadcrumb-item', 'Dashboard')</span>
            </nav>

            {{-- Mobile page title --}}
            <p class="sm:hidden font-bold text-sm text-slate-900 flex-1 truncate">@yield('page-title', 'Dashboard')</p>

            {{-- Right: pending badge + user --}}
            <div class="ml-auto flex items-center gap-3">
                @php
                    try { $navPending = \DB::table('surat')->where('status','pending')->whereNull('deleted_at')->count(); }
                    catch(\Exception $e) { $navPending = 0; }
                @endphp
                @if($navPending > 0)
                    <a href="{{ route('admin.master.surat.index') }}"
                       class="hidden sm:inline-flex items-center gap-1.5 rounded border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 hover:bg-amber-100 transition-colors">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        {{ $navPending }} Pending
                    </a>
                @endif

                {{-- User dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                            class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-slate-100 transition-colors cursor-pointer">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded bg-indigo-100 text-indigo-700 font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                        <span class="hidden md:block text-sm font-semibold text-slate-700">{{ auth()->user()->name ?? 'User' }}</span>
                        <svg class="hidden md:block h-4 w-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 z-50 mt-1 w-44 rounded-lg border border-slate-200 bg-white py-1 text-sm shadow-lg"
                         style="display:none;">
                        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 transition-colors">Profil Saya</a>
                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Pengaturan</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 cursor-pointer">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- PAGE HEADER --}}
        <div class="bg-white border-b border-slate-200 px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-lg font-bold text-slate-900">@yield('page-title', 'Dashboard')</h1>
                <div>@yield('page-actions')</div>
            </div>
        </div>

        {{-- CONTENT --}}
        <main class="flex-1 px-4 sm:px-6 py-6 min-w-0 max-w-full">
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <svg class="h-4 w-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <svg class="h-4 w-4 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="bg-white border-t border-slate-200 px-4 sm:px-6 py-3 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} E-Desa &mdash; Sistem Administrasi Desa Digital
        </footer>
    </div>

</div>

<script>
    if (window.jQuery && $.fn.dataTable) {
        $.fn.dataTable.ext.errMode = 'none';
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari data...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s.d. _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 s.d. 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok ditemukan",
                emptyTable: "Tidak ada data pada tabel ini",
                paginate: {
                    first: "«",
                    last: "»",
                    next: "›",
                    previous: "‹"
                }
            },
            pageLength: 10,
            autoWidth: false,
            responsive: true
        });
    }
</script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        initSearchableSelects();
    });

    // Auto-focus search input immediately upon opening dropdown
    $(document).on('select2:open', function() {
        setTimeout(function() {
            var searchInput = document.querySelector('.select2-container--open .select2-search__field');
            if (searchInput) {
                searchInput.focus();
            }
        }, 10);
    });

    // Auto-init for all selects across pages and dynamic modals
    function initSearchableSelects(container) {
        if (!window.jQuery || !$.fn.select2) return;

        var $root = container ? $(container) : $(document);

        $root.find('select:not(.select2-hidden-accessible):not([data-no-search]):not(.no-search):not(.toolbar-select):not(.no-select2):not([name$="_length"])').each(function() {
            var $select = $(this);
            if ($select.closest('#wordLetterEditor, [x-data*="wordLetterEditor"], .editor-toolbar, .no-select2, [data-no-search]').length || $select.hasClass('no-search') || $select.hasClass('no-select2')) {
                return;
            }
            var optionCount = $select.find('option').length;
            var isExplicit = $select.hasClass('searchable-select') || $select.data('search') === true;
            var isDataTable = $select.attr('name') && $select.attr('name').indexOf('_length') !== -1;

            // Target any select with >= 3 options or explicitly marked
            if ((isExplicit || optionCount >= 3) && !isDataTable) {
                var firstOptText = $select.find('option:first').text();
                var firstOptVal = $select.find('option:first').val();
                var placeholder = $select.attr('placeholder') || (firstOptVal === '' ? firstOptText : 'Pilih atau cari...');

                var $modalParent = $select.closest('.fixed, [x-show], .modal');
                var dropdownParent = $modalParent.length ? $modalParent : $(document.body);

                try {
                    $select.select2({
                        placeholder: placeholder,
                        allowClear: !$select.prop('required'),
                        width: '100%',
                        dropdownParent: dropdownParent,
                        // Show search box when 5 or more options, hide if fewer
                        minimumResultsForSearch: (optionCount >= 5 || isExplicit) ? 0 : Infinity,
                        language: {
                            noResults: function() {
                                return "Data tidak ditemukan";
                            },
                            searching: function() {
                                return "Mencari...";
                            }
                        }
                    });

                    // Sync changes seamlessly with Alpine.js x-model
                    $select.on('change.select2', function() {
                        this.dispatchEvent(new Event('input', { bubbles: true }));
                        this.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                } catch(e) {}
            }
        });
    }

    // Debounced MutationObserver to detect new DOM elements and modal toggles
    var select2Timeout;
    if (window.MutationObserver) {
        var observer = new MutationObserver(function(mutations) {
            clearTimeout(select2Timeout);
            select2Timeout = setTimeout(function() {
                initSearchableSelects(document);
            }, 50);
        });
        observer.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class'] });
    }
</script>

@stack('scripts')
</body>
</html>
