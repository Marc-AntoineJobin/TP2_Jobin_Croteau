<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;



class UserTest extends TestCase
{
    use DatabaseMigrations; 
    use WithFaker;

    public function test_can_create_a_new_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_cant_create_a_new_user_with_invalid_email()
    {
        $user = User::factory()->create([
            'email' => '',
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_can_get_a_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_can_update_a_user()
    {
        $user = User::factory()->create();

        $user->UserRepository->update([
            'name' => 'Marco-polo',
        ]);
        $user->save();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Marco-polo',
            'email' => $user->email,
        ]);
    }

    public function test_can_delete_a_user()
    {
        $user = User::factory()->create();

        $user->UserRepository->delete();
        $user->save();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
