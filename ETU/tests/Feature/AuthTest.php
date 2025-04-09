<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_throttle_signup_under_5_per_minute(): void
    {

        $user = [
            "email" => "marcantoinejobin7@gmail.com",
            "password" => "123abc",
            "first_name" => "Marc-Antoine",
            'last_name' => "Jobin",
            "login" => "Marc-AntoineJ"
        ];

        for($i = 0; $i < 4; $i++){
            $user['login'] = $user['login'] . $i;
            $user['email'] = $user['email'] . $i;
            $response = $this->post('/api/signup', $user);
        }
        $response->assertStatus(201);
    }

    public function test_throttle_signup_over_5_per_minute(): void
    {

        $user = [
            "email" => "marcantoinejobin7@gmail.com",
            "password" => "123abc",
            "first_name" => "Marc-Antoine",
            'last_name' => "Jobin",
            "login" => "Marc-AntoineJ"
        ];

        for($i = 0; $i < 7; $i++){
            $user['login'] = $user['login'] . $i;
            $user['email'] = $user['email'] . $i;
            $response = $this->post('/api/signup', $user);
        }

        $response->assertStatus(429);
    }

    public function test_throttle_signin_under_5_per_minute(): void
    {

        $user = [
            "email" => "marcantoinejobin70@gmail.com",
            "password" => "123abcd",
            "first_name" => "Marc-Antoined",
            'last_name' => "Jobind",
            "login" => "Marc-AntoineJd"
        ];
        $response = $this->post('/api/signup', $user);
        $response->assertStatus(201);
        for($i = 0; $i < 3; $i++){
            $response = $this->post('/api/signin', $user);
            $response->assertStatus(200);
            $response = $this->post('/api/signout', $user);
        }

    }

    public function test_throttle_signin_over_5_per_minute(): void
    {

        $user = [
            "email" => "marcantoinejobin7@gmail.com",
            "password" => "123abc",
            "first_name" => "Marc-Antoine",
            'last_name' => "Jobin",
            "login" => "Marc-AntoineJ"
        ];
        $response = $this->post('/api/signup', $user);

        for($i = 0; $i < 7; $i++){
            $response = $this->post('/api/signin', $user);
        }

        $response->assertStatus(429);
    }

    //TODO est ce quils vont passer si jai pas sigin ou signup avant?
    public function test_throttle_signout_under_5_per_minute(): void
    {
        $user = [
            "email" => "marcantoinejobin7@gmail.com",
            "password" => "123abc",
            "first_name" => "Marc-Antoine",
            'last_name' => "Jobin",
            "login" => "Marc-AntoineJ"
        ];
        $response = $this->get('/api/signup', $user);
        $response->assertStatus(201);
        for($i = 0; $i < 3; $i++){
            $response = $this->get('/api/signin', $user);
            $response = $this->post('/api/signout', $user);
        }

        $response->assertStatus(204);
    }

    public function test_throttle_signout_over_5_per_minute(): void
    {

        $user = [
            "email" => "marcantoinejobin7@gmail.com",
            "password" => "123abc",
            "first_name" => "Marc-Antoine",
            'last_name' => "Jobin",
            "login" => "Marc-AntoineJ"
        ];
        //$response = $this->get('/api/signup', $user);

        for($i = 0; $i < 7; $i++){
            $response = $this->post('/api/signout', $user);
        }

        $response->assertStatus(429);
    }
    public function test_register_successful(): void
    {
    $user = [
        "email" => "marcantoinejobin712@gmail.com",
            "password" => "123abc",
            "first_name" => "Marc-Antoine",
            'last_name' => "Jobin",
            "login" => "Marc-AntoineJ"
    ];

    $response = $this->post('/api/signup', $user);

    $response->assertStatus(201);
    $response->assertJson([
        'message' => 'User created successfully',
        'user' => [
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'login' => $user['login']
        ]
    ]);
    }
    //jsp pk il me donne un 500
    public function test_register_missing_fields(): void
    {
    $user = [
        "email" => "",
        "password" => "",
        "first_name" => "",
        "last_name" => "",
        "login" => ""
    ];

    $response = $this->post('/api/signup', $user);

    $response->assertStatus(500);
    }

    public function test_register_invalid_email(): void
    {
    $user = [
        "email" => "invalid-email",
        "password" => "password123",
        "first_name" => "Test",
        "last_name" => "User",
        "login" => "testuser"
    ];

    $response = $this->post('/api/signup', $user);

    $response->assertStatus(500);
    }
    public function test_login_successful(): void
    {
        $user = [
            "email" => "marcantoinejobin71122@gmail.com",
                "password" => "123abc",
                "first_name" => "Marc-Antoine",
                'last_name' => "Jobin",
                "login" => "Marc-AntoineJ"
        ];
    $response = $this->post('/api/signup', $user);
    $response->assertStatus(201);
    $response = $this->post('/api/signin', [
        'login' => $user->login,
        'password' => $user->password,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['token']);
    }

    public function test_login_missing_field(): void
    {
        $user = [
            "email" => "marcantoinejobin7113422@gmail.com",
                "password" => "123abc",
                "first_name" => "Marc-Antoine",
                'last_name' => "Jobin",
                "login" => "Marc-AntoineJ"
        ];
    $response = $this->post('/api/signup', $user);
    $response->assertStatus(201);
    $response = $this->post('/api/signin', [
        'password' => $user->password,
    ]);

    $response->assertStatus(422);
    }
    public function test_login_non_existent_user(): void
    {
    $response = $this->post('/api/signin', [
        'login' => 'nonexistentuser',
        'password' => 'password123',
    ]);

    $response->assertStatus(401);
    }
    public function test_logout_successful(): void
    {
        $user = [
            "email" => "marcantoinejobin71122@gmail.com",
                "password" => "123abc",
                "first_name" => "Marc-Antoine",
                'last_name' => "Jobin",
                "login" => "Marc-AntoineJ"
        ];
    $response = $this->post('/api/signup', $user);
    $response = $this->post('/api/signin', [
        'login' => $user->login,
        'password' => $user->password,
    ]);

    $response->assertStatus(200);
    $token = $response->json('token');

    $response = $this->post('/api/signout', [
        'login' => $user->login,
        'password' => $user->password,
    ]);

    $response->assertStatus(204);
    }

    public function test_logout_non_existent_user(): void
    {
    $response = $this->post('/api/signout', [
        'login' => 'nonexistentuser',
        'password' => 'password123',
    ]);

    $response->assertStatus(401);
    }
}
