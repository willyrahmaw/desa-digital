<?php

namespace App\Services;

use App\Interfaces\SuratRepositoryInterface;
use App\Models\Surat;
use App\Models\Penduduk;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class SuratService
{
    protected SuratRepositoryInterface $suratRepository;

    public function __construct(SuratRepositoryInterface $suratRepository)
    {
        $this->suratRepository = $suratRepository;
    }

    public function getAll(): Collection
    {
        return $this->suratRepository->all();
    }

    public function getPaginatedList(int $perPage = 10, string $search = '', array $filters = []): LengthAwarePaginator
    {
        $query = Surat::with(['templateSurat', 'penduduk.dataSosial', 'ttdOlehPerangkat']);

        if (!empty($search)) {
            $query->where('no_surat', 'like', "%{$search}%")
                  ->orWhereHas('penduduk', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
        }

        if (!empty($filters['status_pengajuan'])) {
            $query->where('status_pengajuan', $filters['status_pengajuan']);
        }

        $query->latest();

        return $query->paginate($perPage);
    }

    public function store(array $data): Surat
    {
        $data['uuid'] = (string) Str::uuid();
        $data['status_pengajuan'] = $data['status_pengajuan'] ?? 'Pending';
        $data['tanggal_pengajuan'] = now()->toDateString();
        
        $verifyUrl = route('public.verifikasi', $data['uuid']);
        $data['qr_code_path'] = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($verifyUrl);

        $surat = $this->suratRepository->create($data);
        $this->syncDataSosialForSktm($surat);

        return $surat;
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->suratRepository->find($id);
        if (!$record) {
            return false;
        }

        if (isset($data['status_pengajuan']) && $data['status_pengajuan'] === 'Disetujui' && $record->status_pengajuan !== 'Disetujui') {
            $data['tanggal_persetujuan'] = now()->toDateString();
            $data['ttd_oleh_perangkat_id'] = $data['ttd_oleh_perangkat_id'] ?? auth()->user()->perangkatDesa?->id;
        }

        $updated = $record->update($data);
        if ($updated) {
            $this->syncDataSosialForSktm($record->fresh());
        }

        return $updated;
    }

    public function syncDataSosialForSktm(Surat $surat): void
    {
        $template = $surat->templateSurat;
        $jenisName = strtolower($template?->nama ?? $surat->jenis_surat ?? '');
        $jenisKode = strtolower($template?->kode_surat ?? '');

        // Sync with DataSosial table if this is SKTM (Surat Keterangan Tidak Mampu)
        if (str_contains($jenisName, 'tidak mampu') || str_contains($jenisName, 'sktm') || str_contains($jenisKode, 'sktm')) {
            if ($surat->penduduk_nik) {
                \App\Models\DataSosial::updateOrCreate(
                    ['penduduk_nik' => $surat->penduduk_nik],
                    [
                        'layak_sktm' => true,
                        'keterangan' => 'Telah terbit/diajukan SKTM No: ' . ($surat->no_surat ?: 'Pengajuan Baru') . ' untuk keperluan: ' . ($surat->keperluan ?? '-'),
                        'verifikator_id' => auth()->id() ?? $surat->petugas_id,
                        'tanggal_verifikasi' => now()->toDateString(),
                    ]
                );
            }
        }
    }

    public function delete(int $id): bool
    {
        return $this->suratRepository->delete($id);
    }

    public function generateLetterContent(Surat $surat): string
    {
        $template = $surat->templateSurat;
        if (!$template) {
            return '';
        }

        $body = $template->canvas_json ?? $template->content ?? '';

        // If legacy Fabric.js JSON was stored, extract text objects or default template
        if (str_starts_with(trim($body), '{') && str_contains($body, '"objects"')) {
            $json = json_decode($body, true);
            $extractedHtml = '';
            if (!empty($json['objects'])) {
                foreach ($json['objects'] as $obj) {
                    if (isset($obj['text'])) {
                        $align = $obj['textAlign'] ?? 'left';
                        $weight = ($obj['fontWeight'] ?? '') === 'bold' ? 'font-weight:bold;' : '';
                        $extractedHtml .= "<p style=\"text-align:{$align};{$weight}\">" . nl2br(e($obj['text'])) . "</p>";
                    }
                }
            }
            $body = $extractedHtml ?: $body;
        }

        $penduduk = $surat->penduduk;
        $settings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();

        $namaDesa = $settings['nama_desa'] ?? 'Desa Candraloka';
        $namaKecamatan = $settings['kecamatan'] ?? 'Kecamatan Astraguna';
        $namaKabupaten = $settings['kabupaten'] ?? 'Kabupaten Nirwana Raya';
        $namaKades = $settings['nama_kades'] ?? 'Ki Ageng Suryakencana, S.Sos';
        $nipKades = $settings['nip_kades'] ?? '-';

        // Officer / Signer details
        $signer = $surat->ttdOlehPerangkat;
        $signerNama = $signer ? $signer->nama : $namaKades;
        $signerJabatan = $signer && $signer->jabatan ? $signer->jabatan->nama : 'Kepala Desa ' . $namaDesa;
        $signerNip = $signer && $signer->nip ? 'NIP. ' . $signer->nip : '';

        $qrCodeImg = '';

        $genderStr = '-';
        if ($penduduk) {
            $genderStr = $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
        }

        $replacements = [
            '[NOMOR_SURAT]' => $surat->no_surat ?? '.../SRT/' . date('Y'),
            '[NAMA]' => $penduduk->nama ?? '-',
            '[NAMA_LENGKAP]' => $penduduk->nama ?? '-',
            '[NIK]' => $penduduk->nik ?? '-',
            '[NO_KK]' => $penduduk->no_kk ?? '-',
            '[TEMPAT_LAHIR]' => $penduduk->tempat_lahir ?? '-',
            '[TANGGAL_LAHIR]' => $penduduk && $penduduk->tanggal_lahir ? $penduduk->tanggal_lahir->translatedFormat('d F Y') : '-',
            '[TEMPAT_TANGGAL_LAHIR]' => ($penduduk->tempat_lahir ?? '-') . ', ' . ($penduduk && $penduduk->tanggal_lahir ? $penduduk->tanggal_lahir->translatedFormat('d F Y') : '-'),
            '[JENIS_KELAMIN]' => $genderStr,
            '[AGAMA]' => $penduduk && $penduduk->agama ? $penduduk->agama->nama : ($penduduk->agama_id ?? '-'),
            '[STATUS_KAWIN]' => $penduduk && $penduduk->statusKawin ? $penduduk->statusKawin->nama : '-',
            '[PEKERJAAN]' => $penduduk && $penduduk->pekerjaan ? $penduduk->pekerjaan->nama : '-',
            '[PENDIDIKAN]' => $penduduk && $penduduk->pendidikan ? $penduduk->pendidikan->nama : '-',
            '[KEWARGANEGARAAN]' => $penduduk && $penduduk->kewarganegaraan ? $penduduk->kewarganegaraan->nama : 'WNI',
            '[ALAMAT]' => $penduduk->alamat ?? '-',
            '[DUSUN]' => $penduduk && $penduduk->dusun ? $penduduk->dusun->nama : '-',
            '[RT]' => $penduduk && $penduduk->rt ? $penduduk->rt->nomor : '-',
            '[RW]' => $penduduk && $penduduk->rw ? $penduduk->rw->nomor : '-',
            '[KEPERLUAN]' => $surat->keperluan ?? '-',
            '[TANGGAL_PENGESAHAN]' => $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->translatedFormat('d F Y') : now()->translatedFormat('d F Y'),
            '[TANGGAL_SURAT]' => $surat->tanggal_persetujuan ? \Carbon\Carbon::parse($surat->tanggal_persetujuan)->translatedFormat('d F Y') : now()->translatedFormat('d F Y'),
            '[NAMA_KADES]' => $signerNama,
            '[JABATAN_KADES]' => $signerJabatan,
            '[NIP_KADES]' => $signerNip,
            '[NAMA_LURAH]' => $signerNama,
            '[JABATAN_LURAH]' => $signerJabatan,
            '[NAMA_DESA]' => $namaDesa,
            '[NAMA_KECAMATAN]' => $namaKecamatan,
            '[NAMA_KABUPATEN]' => $namaKabupaten,
            '[QR_CODE]' => '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $body);
    }
}
