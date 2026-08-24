<?php

namespace Tests\Feature;

use App\Models\KlasifikasiSurat;
use App\Models\PengaturanPenomoran;
use App\Models\RiwayatNomorSurat;
use App\Models\User;
use App\Services\DocumentNumberService;
use App\Services\DocumentSequenceService;
use App\Services\DocumentValidationService;
use App\Services\DocumentHistoryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DocumentNumberEngineTest extends TestCase
{
    use DatabaseTransactions;

    protected DocumentNumberService $numberService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numberService = $this->app->make(DocumentNumberService::class);
    }

    public function test_can_generate_document_number_with_tokens()
    {
        // 1. Setup Klasifikasi & Format
        $klasifikasi = KlasifikasiSurat::firstOrCreate(
            ['kode' => 'TEST999'],
            [
                'nama' => 'Surat Uji Coba Engine',
                'kategori' => 'TEST',
                'status' => 'aktif',
                'urutan' => 99
            ]
        );

        $format = PengaturanPenomoran::create([
            'nama_format' => 'Format Test Default',
            'jenis_surat' => 'Surat Uji Coba Engine',
            'format_nomor' => '{kode}/{nomor}/{desa}/{bulan_romawi}/{tahun}',
            'reset_nomor' => 'yearly',
            'digit_nomor' => 3,
            'status' => true
        ]);

        // 2. Generate Number
        $number = $this->numberService->generateNumber(
            'Surat Uji Coba Engine',
            '2026-07-29',
            []
        );

        // 3. Assertions
        $desaName = \App\Models\Pengaturan::where('key', 'nama_desa')->value('value') ?? 'Desa Candraloka';
        $expectedNumber = 'TEST999/001/' . $desaName . '/VII/2026';
        $this->assertEquals($expectedNumber, $number);

        // Assert logged in history
        $this->assertDatabaseHas('riwayat_nomor_surat', [
            'nomor_surat' => $expectedNumber,
            'jenis_surat' => 'Surat Uji Coba Engine',
        ]);
    }

    public function test_sequential_numbering_increments_correctly()
    {
        PengaturanPenomoran::create([
            'nama_format' => 'Format SKU',
            'jenis_surat' => 'Surat Keterangan Usaha',
            'format_nomor' => 'SKU/{nomor}/{tahun}',
            'reset_nomor' => 'yearly',
            'digit_nomor' => 4,
            'status' => true
        ]);

        $num1 = $this->numberService->generateNumber('Surat Keterangan Usaha', '2026-07-29');
        $num2 = $this->numberService->generateNumber('Surat Keterangan Usaha', '2026-07-29');
        $num3 = $this->numberService->generateNumber('Surat Keterangan Usaha', '2026-07-29');

        $this->assertEquals('SKU/0001/2026', $num1);
        $this->assertEquals('SKU/0002/2026', $num2);
        $this->assertEquals('SKU/0003/2026', $num3);
    }

    public function test_preview_does_not_increment_sequence()
    {
        $format = PengaturanPenomoran::create([
            'nama_format' => 'Format SKD',
            'jenis_surat' => 'Surat Keterangan Domisili',
            'format_nomor' => 'SKD/{nomor}/{tahun}',
            'reset_nomor' => 'yearly',
            'digit_nomor' => 3,
            'status' => true
        ]);

        $preview1 = $this->numberService->previewNextNumber($format, '2026-07-29');
        $preview2 = $this->numberService->previewNextNumber($format, '2026-07-29');

        // Previews should be identical
        $this->assertEquals('SKD/001/2026', $preview1);
        $this->assertEquals('SKD/001/2026', $preview2);

        // Generating actual number should give 001
        $actual = $this->numberService->generateNumber('Surat Keterangan Domisili', '2026-07-29');
        $this->assertEquals('SKD/001/2026', $actual);

        // Subsequent preview should now show 002
        $preview3 = $this->numberService->previewNextNumber($format, '2026-07-29');
        $this->assertEquals('SKD/002/2026', $preview3);
    }

    public function test_validation_prevents_duplicate_numbers()
    {
        $validationService = $this->app->make(DocumentValidationService::class);

        RiwayatNomorSurat::create([
            'nomor_surat' => 'DUPLICATE/001/2026',
            'jenis_surat' => 'Test',
            'tanggal' => '2026-07-29',
            'status' => 'digunakan'
        ]);

        $this->assertFalse($validationService->isNumberUnique('DUPLICATE/001/2026'));
        $this->assertTrue($validationService->isNumberUnique('UNIQUE/002/2026'));
    }
}
