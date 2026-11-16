<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SISTEM PARKIR');
    }

    public function test_admin_can_login_and_redirect_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    public function test_petugas_can_login_and_redirect_to_petugas_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'petugas',
            'password' => 'petugas',
        ]);

        $response->assertRedirect('/petugas/dashboard');
        $this->assertAuthenticated();
    }

    public function test_owner_can_login_and_redirect_to_owner_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'owner',
            'password' => 'owner',
        ]);

        $response->assertRedirect('/owner/dashboard');
        $this->assertAuthenticated();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::where('username', 'petugas')->first();
        $user->update(['status_aktif' => 0]);

        $response = $this->post('/login', [
            'username' => 'petugas',
            'password' => 'petugas',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_petugas_cannot_access_admin_user_management(): void
    {
        $petugas = User::where('username', 'petugas')->first();

        $response = $this->actingAs($petugas)->get('/admin/users');
        $response->assertStatus(403);
    }
}
