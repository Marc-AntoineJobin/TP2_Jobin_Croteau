<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Film;
use App\Models\Critic;

class CriticTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_critic_successfully()
    {
        $this->seed();

        $user = User::factory()->create();
        $film = Film::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $data = [
            'score' => '4.5',
            'comment' => 'Excellent film!',
            'film_id' => $film->id,
            'user_id' => $user->id
        ];
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/critics', $data);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'score' => '4.5',
                'comment' => 'Excellent film!',
                'film_id' => $film->id,
                'user_id' => $user->id
            ]);

        $this->assertDatabaseHas('critics', $data);
    }

}
