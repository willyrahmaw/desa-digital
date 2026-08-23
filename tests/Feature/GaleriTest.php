<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GaleriTest extends TestCase
{
    use DatabaseTransactions;

    public function test_galeri_page_loads_successfully()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.master.galeri.index'));

        $response->assertStatus(200);
    }
}
