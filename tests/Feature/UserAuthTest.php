<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserAuthTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=SystemAdminSeeder');
    }

    public function test_user_can_login()
    {
        $data = [
            'email' => 'admin@example.com',
            'password' => 'password'
        ];
        $this->post(url('/api/login'), $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'access_token',
                    'token_type',
                    'user'
                ],
                'message'
            ]);
    }

    public function test_register_user()
    {
        $registerData = [
            'name' => 'Robius Sani',
            'email' => 'rsani152@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $this->post(url('/api/register'), $registerData)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'access_token',
                    'token_type',
                    'user'
                ],
                'message'
            ]);
    }

    public function test_logged_in_user_data()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
        $response = $this->get('/api/user');
        $response->assertOk();
    }

    public function test_user_logout()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-tasks']
        );
        $response = $this->post('/api/logout');
        $response->assertOk();
    }
}
