<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use App\Models\TemplateSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SuratCreationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authenticated_admin_can_view_create_surat_page()
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.master.surat.create'));

        $response->assertStatus(200);
        $response->assertSee('Buat & Generate Surat');
    }

    public function test_authenticated_admin_can_submit_surat_request()
    {
        $user = User::first() ?? User::factory()->create();
        $penduduk = Penduduk::first();
        $template = TemplateSurat::where('nama', 'not like', '%SKTM%')
            ->where('nama', 'not like', '%Tidak Mampu%')
            ->first() ?? TemplateSurat::first();

        $this->assertNotNull($penduduk, 'Penduduk must exist in seeded database');
        $this->assertNotNull($template, 'Template must exist in seeded database');

        $response = $this->actingAs($user)->post(route('admin.master.surat.store'), [
            'template_surat_id' => $template->id,
            'penduduk_nik' => $penduduk->nik,
            'keperluan' => 'Keperluan Administrasi Resmi',
        ]);

        $response->assertRedirect(route('admin.master.surat.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('surat', [
            'template_id' => $template->id,
            'status' => 'pending',
        ]);
    }
}
