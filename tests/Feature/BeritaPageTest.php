<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeritaPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_access_create_berita_page()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.master.berita.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.master.berita.create');
    }

    public function test_can_upload_image_via_ckeditor_adapter()
    {
        Storage::fake('public');
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('artikel_photo.jpg');

        $response = $this->postJson(route('admin.master.berita.upload-image'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['uploaded', 'url']);
        $this->assertTrue($response->json('uploaded'));
    }

    public function test_can_access_edit_berita_page()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $kategori = KategoriBerita::firstOrCreate(
            ['slug' => 'pengumuman'],
            ['nama' => 'Pengumuman Desa']
        );

        $berita = Berita::create([
            'judul' => 'Pengumuman Posyandu Desa',
            'slug' => 'pengumuman-posyandu-desa',
            'isi' => '<p>Isi pengumuman posyandu...</p>',
            'status' => 'published',
            'user_id' => $user->id,
            'kategori_berita_id' => $kategori->id,
        ]);

        $response = $this->get(route('admin.master.berita.edit', $berita->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.master.berita.edit');
    }
}
