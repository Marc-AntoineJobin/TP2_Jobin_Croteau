<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;
use App\Http\Controllers\Controller;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_register_successfully_gives_201()
    {
        $this->seed();
        $json = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'pika@chu.com',
            'login' => 'ash',
            'password' => 'pikachu',
        ];
        $response = $this->postJson('/api/signup', $json);
        $response->assertStatus(201);
        $response->assertJsonStructure(['message', 'user']);
    }

    public function test_login_successfully_gives_200()
    {
        $this->seed();
        $password = 'pikachu';
        $user = User::factory()->create([
            'email' => 'pika@chu.com',
            'login' => 'ash',
            'password' => bcrypt($password)
        ]);

        $response = $this->postJson('/api/signin', [
            'login' => $user->login,
            'password' => $password
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_incorrect_password()
    {
        $this->seed();
        $user = User::factory()->create([
            'login' => 'ash',
            'password' => bcrypt('correct_password')
        ]);

        $response = $this->postJson('/api/signin', [
            'login' => 'ash',
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(401);
    }

    public function test_logout_fails_when_not_authenticated()
    {
        $this->seed();
        $response = $this->postJson('/api/signout');
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_logout_successfully_gives_204()
    {
        $this->seed();

        $user = User::factory()->create([
            'email' => 'pika@chu.com',
            'login' => 'pika',
            'password' => bcrypt('pikachu'),
        ]);

        $response = $this->postJson('/api/signin', [
            'login' => $user->login,
            'password' => 'pikachu',
        ]);

        $token = $response['token'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/signout');

        $response->assertStatus(204);
    }


    public function test_register_fails_with_invalid_data()
    {
        $this->seed();
        $response = $this->postJson('/api/signup', [
            'first_name' => '',
            'email' => 'not-an-email',
            'password' => '',
        ]);
        $response->assertStatus(422);
    }

    public function test_login_fails_with_invalid_data()
    {
        $this->seed();
        $response = $this->postJson('/api/signin', [
            'login' => '',
            'password' => ''
        ]);
        $response->assertStatus(422);
    }

    public function test_too_many_register_requests_gives_429()
    {
        $this->seed();
        $password = 'pikachu';

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/signup', [
                'first_name' => 'T',
                'last_name' => 'U',
                'email' => "$i@chu.com",
                'login' => "login$i",
                'password' => $password
            ]);
        }

        $response = $this->postJson('/api/signup', [
            'first_name' => 'T',
            'last_name' => 'U',
            'email' => "6@chu.com",
            'login' => "login6",
            'password' => $password
        ]);

        $response->assertStatus(429);
    }

    public function test_too_many_login_requests_gives_429()
    {
        $this->seed();
        $password = 'pikachu';
        $user = User::factory()->create([
            'login' => 'ash',
            'password' => bcrypt($password)
        ]);

        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/api/signin', [
                'login' => $user->login,
                'password' => $password
            ]);
        }

        $response = $this->postJson('/api/signin', [
            'login' => $user->login,
            'password' => $password
        ]);

        $response->assertStatus(429);
    }
}
