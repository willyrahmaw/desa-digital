<?php

namespace Tests\Feature;

use App\Models\KategoriBerita;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KategoriBeritaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_access_kategori_berita_index()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.master.kategori_berita.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.master.kategori_berita.index');
    }

    public function test_can_create_kategori_berita()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('admin.master.kategori_berita.store'), [
            'nama' => 'Teknologi & Inovasi Desa',
        ]);

        $response->assertRedirect(route('admin.master.kategori_berita.index'));
        $this->assertDatabaseHas('kategori_berita', [
            'nama' => 'Teknologi & Inovasi Desa',
            'slug' => 'teknologi-inovasi-desa',
        ]);
    }

    public function test_can_update_kategori_berita()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $kategori = KategoriBerita::create([
            'nama' => 'Kategori Lama',
            'slug' => 'kategori-lama',
        ]);

        $response = $this->put(route('admin.master.kategori_berita.update', $kategori->id), [
            'nama' => 'Kategori Baru Terupdate',
        ]);

        $response->assertRedirect(route('admin.master.kategori_berita.index'));
        $this->assertDatabaseHas('kategori_berita', [
            'id' => $kategori->id,
            'nama' => 'Kategori Baru Terupdate',
            'slug' => 'kategori-baru-terupdate',
        ]);
    }
}
