<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_registeration(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '1234567890',
            'role' => 'customer',
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message', 'token', 'user' => ['id', 'name', 'role']
        ]);
    }

    public function test_login(){
        $user = User::factory()->create([
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'message', 'token', 'user' => ['id', 'name', 'role']
        ]);
    }
}
