<?php

namespace Tests\Feature;

use App\Models\DataSosial;
use App\Models\Penduduk;
use App\Models\Surat;
use App\Models\TemplateSurat;
use App\Models\User;
use App\Services\SuratService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SktmDataSosialSyncTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sktm_creation_automatically_syncs_with_data_sosial()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $penduduk = Penduduk::first();
        if (!$penduduk) {
            $this->markTestSkipped('Penduduk data not present');
        }

        $template = TemplateSurat::firstOrCreate(
            ['nama' => 'Surat Keterangan Tidak Mampu (SKTM)'],
            [
                'kategori_surat' => 'SKTM',
                'content' => 'Template SKTM untuk uji coba sinkronisasi'
            ]
        );

        $suratService = $this->app->make(SuratService::class);

        // Store new SKTM request
        $surat = $suratService->store([
            'template_surat_id' => $template->id,
            'penduduk_nik' => $penduduk->nik,
            'keperluan' => 'Permohonan Bantuan Beasiswa',
        ]);

        // Assert DataSosial record exists and layak_sktm is true
        $this->assertDatabaseHas('data_sosial', [
            'penduduk_nik' => $penduduk->nik,
            'layak_sktm' => 1,
        ]);

        $dataSosial = DataSosial::where('penduduk_nik', $penduduk->nik)->first();
        $this->assertNotNull($dataSosial);
        $this->assertTrue((bool)$dataSosial->layak_sktm);
        $this->assertStringContainsString('SKTM', $dataSosial->keterangan);
    }
}
