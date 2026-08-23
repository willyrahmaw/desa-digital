<?php

namespace Tests\Feature;

use App\Models\DataSosial;
use App\Models\Penduduk;
use App\Models\TemplateSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SktmEligibilityValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_prevents_sktm_creation_for_ineligible_resident()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $penduduk = Penduduk::first();
        if (!$penduduk) {
            $this->markTestSkipped('Penduduk data not present');
        }

        // Mark resident as INELIGIBLE for SKTM in DataSosial
        DataSosial::updateOrCreate(
            ['penduduk_nik' => $penduduk->nik],
            [
                'layak_sktm' => false,
                'keterangan' => 'Hasil verifikasi mampu secara ekonomi',
                'verifikator_id' => $user->id,
                'tanggal_verifikasi' => now()->toDateString(),
            ]
        );

        $template = TemplateSurat::firstOrCreate(
            ['nama' => 'Surat Keterangan Tidak Mampu (SKTM)'],
            [
                'kategori_surat' => 'SKTM',
                'content' => 'Content SKTM'
            ]
        );

        // Submit SKTM request for ineligible resident
        $response = $this->post(route('admin.master.surat.store'), [
            'template_surat_id' => $template->id,
            'penduduk_nik' => $penduduk->nik,
            'keperluan' => 'Permohonan Keringanan Biaya',
        ]);

        // Should be rejected by validation with session error for template_surat_id
        $response->assertSessionHasErrors(['template_surat_id']);
    }
}
