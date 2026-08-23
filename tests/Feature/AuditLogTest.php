<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use DatabaseTransactions;

    public function test_audit_log_page_loads_successfully()
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.audit.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.audit.index');
    }
}
