<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successful()
    {
        $user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'nama' => 'User Satu',
            'npp' => 'USR001',
            'npp_supervisor' => 'SUP001'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'token',
                         'user' => [
                             'id',
                             'nama',
                             'email',
                             'npp',
                             'npp_supervisor'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Authentication successful',
                 ]);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@gmail.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Invalid credentials'
                 ]);

    }

    public function test_login_validation_fails_when_fields_missing()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }
}
