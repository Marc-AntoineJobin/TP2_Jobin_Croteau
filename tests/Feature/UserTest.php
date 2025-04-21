<?php

namespace Tests\Feature;

use App\Repository\Eloquent\UserRepository;
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
        $this->seed();
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'login' => $user->login,
            'email' => $user->email,
        ]);
    }

    public function test_cant_create_a_new_user_with_invalid_email()
    {
        $this->seed();
        $user = User::factory()->create([
            'email' => 'invalid-email',
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'login' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_can_get_a_user()
    {
        $this->seed();
        $userRepository = new UserRepository(new User());
        $user = $userRepository->getById(1);
        $this->assertNotNull($user);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'login' => $user->login,
            'email' => $user->email,
        ]);
    }

    public function test_can_update_a_user()
    {
        $this->seed();
        $user = User::factory()->create();
        $user->update([
            'login' => 'Marco-polo',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'login' => 'Marco-polo',
            'email' => $user->email,
        ]);
    }


    public function test_can_delete_a_user()
    {
        $this->seed();
        $user = User::factory()->create();
        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'login' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_can_update_password()
    {
        $this->seed();
        $user = User::factory()->create();
        $user->update([
            'password' => bcrypt('bobichou'),
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'login' => $user->login,
            'email' => $user->email,
        ]);
    }

}
