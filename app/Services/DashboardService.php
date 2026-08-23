<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    public function getStats(): array
    {
        $today      = now()->toDateString();
        $thisYear   = now()->year;
        $thisMonth  = now()->month;
        $lastMonth  = now()->subMonth()->month;
        $lastMonthY = now()->subMonth()->year;

        // ── Penduduk ──────────────────────────────────────────
        $totalPenduduk  = DB::table('penduduk')->whereNull('deleted_at')->count();
        $totalLaki      = DB::table('penduduk')->whereNull('deleted_at')->where('jenis_kelamin', 'L')->count();
        $totalPerempuan = DB::table('penduduk')->whereNull('deleted_at')->where('jenis_kelamin', 'P')->count();
        $pendudukBaruBulanIni = DB::table('penduduk')
            ->whereNull('deleted_at')
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)
            ->count();
        $totalKK        = DB::table('kartu_keluarga')->count();

        // ── Surat ─────────────────────────────────────────────
        $totalSuratHariIni  = DB::table('surat')->whereDate('created_at', $today)->whereNull('deleted_at')->count();
        $totalSuratBulanIni = DB::table('surat')
            ->whereNull('deleted_at')
            ->whereYear('created_at', $thisYear)
            ->whereMonth('created_at', $thisMonth)
            ->count();
        $totalSuratBulanLalu = DB::table('surat')
            ->whereNull('deleted_at')
            ->whereYear('created_at', $lastMonthY)
            ->whereMonth('created_at', $lastMonth)
            ->count();
        $totalSuratTahunIni = DB::table('surat')->whereNull('deleted_at')->whereYear('created_at', $thisYear)->count();
        $suratPendingCount  = DB::table('surat')->whereNull('deleted_at')->where('status', 'pending')->count();
        $suratApprovedCount = DB::table('surat')->whereNull('deleted_at')->where('status', 'approved')->count();

        // Growth rate bulan ke bulan
        $suratGrowth = $totalSuratBulanLalu > 0
            ? round((($totalSuratBulanIni - $totalSuratBulanLalu) / $totalSuratBulanLalu) * 100, 1)
            : ($totalSuratBulanIni > 0 ? 100 : 0);

        // Surat by template type (top 5)
        $suratByType = DB::table('surat')
            ->join('template_surat', 'surat.template_id', '=', 'template_surat.id')
            ->select('template_surat.nama as jenis', DB::raw('count(*) as total'))
            ->whereNull('surat.deleted_at')
            ->whereYear('surat.created_at', $thisYear)
            ->groupBy('template_surat.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ── Pengaduan ─────────────────────────────────────────
        $totalPengaduan   = DB::table('pengaduan')->whereNull('deleted_at')->count();
        $pengaduanPending = DB::table('pengaduan')->whereNull('deleted_at')->where('status', 'pending')->count();
        $pengaduanProses  = DB::table('pengaduan')->whereNull('deleted_at')->whereIn('status', ['proses', 'process'])->count();
        $pengaduanSelesai = DB::table('pengaduan')->whereNull('deleted_at')->whereIn('status', ['selesai', 'resolved'])->count();

        // ── UMKM & BUMDes ─────────────────────────────────────
        $totalUmkm   = DB::table('umkm_pelaku')->count();
        $totalBumdes = DB::table('bumdes_unit')->count();
        $totalBerita = DB::table('berita')->count();

        // ── Pending approvals dengan detail ───────────────────
        $pendingApprovals = DB::table('surat')
            ->join('penduduk', 'surat.penduduk_nik', '=', 'penduduk.nik')
            ->leftJoin('template_surat', 'surat.template_id', '=', 'template_surat.id')
            ->select('surat.id', 'surat.created_at', 'penduduk.nama as penduduk_nama',
                     'template_surat.nama as jenis_surat_nama')
            ->where('surat.status', 'pending')
            ->whereNull('surat.deleted_at')
            ->latest('surat.created_at')
            ->limit(6)
            ->get();

        // ── Agenda hari ini ───────────────────────────────────
        $agendaHariIni = DB::table('agenda')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->orderBy('tanggal_mulai')
            ->get();

        // ── Recent surat ──────────────────────────────────────
        $recentSurat = DB::table('surat')
            ->join('penduduk', 'surat.penduduk_nik', '=', 'penduduk.nik')
            ->leftJoin('template_surat', 'surat.template_id', '=', 'template_surat.id')
            ->select('surat.id', 'surat.nomor_surat', 'surat.status', 'surat.created_at',
                     'penduduk.nama as penduduk_nama', 'template_surat.nama as jenis_surat_nama')
            ->whereNull('surat.deleted_at')
            ->latest('surat.created_at')
            ->limit(8)
            ->get();

        return [
            'total_penduduk'          => $totalPenduduk,
            'total_laki'              => $totalLaki,
            'total_perempuan'         => $totalPerempuan,
            'penduduk_baru_bulan_ini' => $pendudukBaruBulanIni,
            'total_kk'                => $totalKK,
            'total_surat_hari_ini'    => $totalSuratHariIni,
            'total_surat_bulan_ini'   => $totalSuratBulanIni,
            'total_surat_bulan_lalu'  => $totalSuratBulanLalu,
            'total_surat_tahun_ini'   => $totalSuratTahunIni,
            'surat_pending_count'     => $suratPendingCount,
            'surat_approved_count'    => $suratApprovedCount,
            'surat_growth'            => $suratGrowth,
            'surat_by_type'           => $suratByType,
            'total_pengaduan'         => $totalPengaduan,
            'pengaduan_pending'       => $pengaduanPending,
            'pengaduan_proses'        => $pengaduanProses,
            'pengaduan_selesai'       => $pengaduanSelesai,
            'total_umkm'              => $totalUmkm,
            'total_bumdes'            => $totalBumdes,
            'total_berita'            => $totalBerita,
            'pending_approvals'       => $pendingApprovals,
            'agenda_hari_ini'         => $agendaHariIni,
            'recent_surat'            => $recentSurat,
        ];
    }

    public function getChartData(): array
    {
        $thisYear = now()->year;

        $gender = DB::table('penduduk')
            ->select('jenis_kelamin', DB::raw('count(*) as count'))
            ->whereNull('deleted_at')
            ->groupBy('jenis_kelamin')
            ->get();

        $religion = DB::table('penduduk')
            ->join('agama', 'penduduk.agama_id', '=', 'agama.id')
            ->select('agama.nama as name', DB::raw('count(*) as count'))
            ->whereNull('penduduk.deleted_at')
            ->groupBy('agama.nama')
            ->orderByDesc('count')
            ->get();

        $education = DB::table('penduduk')
            ->join('pendidikan', 'penduduk.pendidikan_id', '=', 'pendidikan.id')
            ->select('pendidikan.nama as name', DB::raw('count(*) as count'))
            ->whereNull('penduduk.deleted_at')
            ->groupBy('pendidikan.nama')
            ->orderByDesc('count')
            ->get();

        $occupation = DB::table('penduduk')
            ->join('pekerjaan', 'penduduk.pekerjaan_id', '=', 'pekerjaan.id')
            ->select('pekerjaan.nama as name', DB::raw('count(*) as count'))
            ->whereNull('penduduk.deleted_at')
            ->groupBy('pekerjaan.nama')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $dusun = DB::table('penduduk')
            ->join('dusun', 'penduduk.dusun_id', '=', 'dusun.id')
            ->select('dusun.nama as name', DB::raw('count(*) as count'))
            ->whereNull('penduduk.deleted_at')
            ->groupBy('dusun.nama')
            ->orderByDesc('count')
            ->get();

        // Monthly surat trend (12 months)
        $suratMonthlyRaw = DB::table('surat')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('count(*) as total'))
            ->whereNull('deleted_at')
            ->whereYear('created_at', $thisYear)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $suratMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $suratMonthly[] = [
                'label' => $monthNames[$m - 1],
                'total' => $suratMonthlyRaw->has($m) ? (int) $suratMonthlyRaw[$m]->total : 0,
            ];
        }

        return [
            'gender'        => $gender,
            'religion'      => $religion,
            'education'     => $education,
            'occupation'    => $occupation,
            'dusun'         => $dusun,
            'surat_monthly' => $suratMonthly,
        ];
    }
}
