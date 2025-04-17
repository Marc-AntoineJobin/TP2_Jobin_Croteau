<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'login' => 'bigboss',
            'password' => Hash::make('bigbosspassword'),
            'email' => 'bigboss@bigboss.com',
            'last_name' => 'boss',
            'first_name' => 'big',
            'role_id' => 2
        ]);
    }
}
