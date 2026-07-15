<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertOk();
    }

    public function test_forgot_password_request_creates_pending_approval(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'student@example.com',
            'requested_password' => 'new-pass-1234',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('password_approvals', [
            'email' => 'student@example.com',
            'requested_password' => 'new-pass-1234',
            'status' => 'pending',
        ]);
    }
}
