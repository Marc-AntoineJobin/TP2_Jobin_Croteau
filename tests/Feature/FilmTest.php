<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Film;
use App\Models\User;
use App\Models\Language;
use Illuminate\Foundation\Testing\WithFaker;

class FilmTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_creates_a_film_successfully()
    {
        $this->seed();
        $admin = User::factory()->create(['role_id' => 2]);
        $this->actingAs($admin, 'sanctum');

        $data = [
            'title' => 'Inception',
            'description' => 'A mind-bending thriller',
            'release_year' => '2010',
            'length' => 148,
            'special_features' => 'Deleted Scenes',
            'image' => 'inception.jpg',
            'rating' => 'PG-13',
            'language_id' => Language::factory()->create()->id
        ];

        $response = $this->postJson('/api/films', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Inception',
                'description' => 'A mind-bending thriller',
                'release_year' => '2010',
            ]);
        $this->assertDatabaseHas('films', $data);
    }

    public function test_updates_a_film_successfully()
    {
        $this->seed();
        $admin = User::factory()->create(['role_id' => 2]);
        $this->actingAs($admin, 'sanctum');

        $film = Film::factory()->create([
            'title' => 'Old Title',
            'description' => 'Old description',
            'release_year' => 2000,
        ]);

        $updatedData = [
            'title' => 'New Title',
            'description' => 'Updated description',
            'release_year' => 2022,
        ];

        $response = $this->putJson("/api/films/{$film->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('films', array_merge(['id' => $film->id], $updatedData));
        // https://www.php.net/manual/en/function.array-merge.php
    }

    public function test_deletes_a_film()
    {
        $this->seed();
        $admin = User::factory()->create(['role_id' => 2]);
        $this->actingAs($admin, 'sanctum');

        $film = Film::factory()->create();

        $response = $this->deleteJson("/api/films/{$film->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Film deleted successfully']);

        $this->assertDatabaseMissing('films', ['id' => $film->id]);
    }
}
