<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $department = \App\Models\Department::create([
            'name' => 'Computer Science',
            'code' => 'CSE'
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'enrollment_no' => '12345678',
            'abc_card_id' => 'abc123456',
            'phone' => '9876543210',
            'dob' => '2000-01-01',
            'gender' => 'male',
            'blood_group' => 'O+',
            'aadhar_no' => '123456789012',
            'guardian_name' => 'Guardian',
            'address' => '123 Test Street',
            'department_id' => $department->id
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'status' => 'pending'
        ]);
    }
}
