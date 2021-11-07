<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
            'email' => 'admin@system.com',
            'password' => '12345678'
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

    /* public function test_register_user()
    {
    } */
}
