<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authenticated_user_can_view_profile_page()
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil Akun');
        $response->assertSee($user->email);
    }

    public function test_user_can_update_profile_info()
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.profile.update'), [
            'name' => 'Nama Pengguna Baru',
            'email' => 'pengguna.baru@desa.id',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user', [
            'id' => $user->id,
            'name' => 'Nama Pengguna Baru',
            'email' => 'pengguna.baru@desa.id',
        ]);
    }

    public function test_user_can_update_password_with_valid_current_password()
    {
        $user = User::first() ?? User::factory()->create();
        $user->update(['password' => Hash::make('password_lama')]);

        $response = $this->actingAs($user)->put(route('admin.profile.password'), [
            'current_password' => 'password_lama',
            'password' => 'password_baru_123',
            'password_confirmation' => 'password_baru_123',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('password_baru_123', $user->password));
    }

    public function test_user_cannot_update_password_with_invalid_current_password()
    {
        $user = User::first() ?? User::factory()->create();
        $user->update(['password' => Hash::make('password_lama')]);

        $response = $this->actingAs($user)->put(route('admin.profile.password'), [
            'current_password' => 'password_salah',
            'password' => 'password_baru_123',
            'password_confirmation' => 'password_baru_123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }
}
