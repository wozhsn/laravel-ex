<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CreatesApplication;
use Tests\TestCase;
use Tymon\JWTAuth\JWTAuth;

class ApiTest extends TestCase
{
    use CreatesApplication, DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUsersList()
    {
        $user = User::find(1);
        $this->json('GET', '/api/users', [], $this->getHeaders($user))
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id', 'name', 'email', 'created_at', 'updated_at'
                ]
            ]);
    }

    /**
     */
    protected function getHeaders($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = app(JWTAuth::class)->fromUser($user);
            app(JWTAuth::class)->setToken($token);

            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }
}
