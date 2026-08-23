@extends('layouts.admin')

@section('title', 'Audit Trail & Log System')
@section('breadcrumb-item', 'Audit Trail')
@section('page-title', 'Catatan Aktivitas & Log Sitem')

@section('content')
<div x-data="{ tab: 'activities' }">

    {{-- Tabs Bar --}}
    <div class="flex border-b border-slate-200 mb-6 bg-white rounded-t-lg px-4 pt-3 shadow-sm">
        <button @click="tab = 'activities'" :class="tab === 'activities' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-4 font-semibold text-sm border-b-2 transition-colors">
            Log Aktivitas Tranksaksi
        </button>
        <button @click="tab = 'logins'" :class="tab === 'logins' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-4 font-semibold text-sm border-b-2 transition-colors">
            Log Riwayat Login
        </button>
    </div>

    <!-- Tab 1: Transaction Activities Table -->
    <div x-show="tab === 'activities'">
        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto p-4">
                <table id="activityTable" class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Pengguna</th>
                            <th class="px-6 py-3">Aktivitas / Tindakan</th>
                            <th class="px-6 py-3">Modul</th>
                            <th class="px-6 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        @foreach ($activities as $act)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-900 font-bold">
                                    {{ $act->user->name ?? 'Sistem / Anonim' }}
                                </td>
                                <td class="px-6 py-4 text-slate-700 font-medium">{{ $act->deskripsi ?? $act->action }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                                        {{ $act->module }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $act->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <!-- Tab 2: Login Logs Table -->
    <div x-show="tab === 'logins'" style="display: none;">
        <x-card class="overflow-hidden p-0">
            <div class="overflow-x-auto p-4">
                <table id="loginTable" class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Nama Pengguna</th>
                            <th class="px-6 py-3">Alamat IP</th>
                            <th class="px-6 py-3">User Agent</th>
                            <th class="px-6 py-3">Waktu Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        @foreach ($logins as $login)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-900 font-bold">
                                    {{ $login->user->name ?? $login->email_percobaan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-mono text-xs">{{ $login->ip_address }}</td>
                                <td class="px-6 py-4 text-slate-500 max-w-md truncate">{{ $login->user_agent }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $login->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#activityTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        order: [[3, 'desc']]
    });

    $('#loginTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 10,
        order: [[3, 'desc']]
    });
});
</script>
@endpush
