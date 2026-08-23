<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SecurityAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_non_super_admin_cannot_access_user_management()
    {
        $operatorRole = Role::firstOrCreate(['name' => 'operator'], ['label' => 'Operator']);
        $operatorUser = User::factory()->create(['role_id' => $operatorRole->id]);

        $response = $this->actingAs($operatorUser)->get(route('admin.user.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_user_management()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin'], ['label' => 'Super Admin']);
        $superAdminUser = User::factory()->create(['role_id' => $superAdminRole->id]);

        $response = $this->actingAs($superAdminUser)->get(route('admin.user.index'));

        $response->assertStatus(200);
    }

    public function test_user_cannot_delete_themselves()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin'], ['label' => 'Super Admin']);
        $superAdminUser = User::factory()->create(['role_id' => $superAdminRole->id]);

        $response = $this->actingAs($superAdminUser)->delete(route('admin.user.destroy', $superAdminUser->id));

        $response->assertRedirect(route('admin.user.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('user', ['id' => $superAdminUser->id]);
    }

    public function test_security_headers_are_present_on_web_responses()
    {
        $response = $this->get(route('public.home'));

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
    }
}
