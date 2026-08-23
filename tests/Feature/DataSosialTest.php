<?php

namespace Tests\Feature;

use App\Models\DataSosial;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DataSosialTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_upsert_data_sosial_without_unique_constraint_violation()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $penduduk = Penduduk::first();
        if (!$penduduk) {
            $this->markTestSkipped('Penduduk data not present');
        }

        // Store first time
        $response1 = $this->post(route('admin.master.data_social.store'), [
            'penduduk_nik' => $penduduk->nik,
            'dtks' => '1',
            'pkh' => '1',
            'bpnt' => '0',
            'keterangan' => 'Uji coba pertama'
        ]);

        $response1->assertRedirect(route('admin.master.data_social.index'));
        $this->assertDatabaseHas('data_sosial', [
            'penduduk_nik' => $penduduk->nik,
            'dtks' => 1,
            'pkh' => 1,
        ]);

        // Store second time for SAME NIK (should update, not crash with duplicate entry)
        $response2 = $this->post(route('admin.master.data_social.store'), [
            'penduduk_nik' => $penduduk->nik,
            'dtks' => '1',
            'pkh' => '0',
            'bpnt' => '1',
            'keterangan' => 'Uji coba pembaruan data'
        ]);

        $response2->assertRedirect(route('admin.master.data_social.index'));
        $this->assertDatabaseHas('data_sosial', [
            'penduduk_nik' => $penduduk->nik,
            'bpnt' => 1,
            'keterangan' => 'Uji coba pembaruan data'
        ]);
    }
}
