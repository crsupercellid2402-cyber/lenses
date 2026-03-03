<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'id'       => (string) Str::ulid(),
            'login'    => 'user1',
            'password' => Hash::make('password'),
        ]);

        User::query()->create([
            'id'       => (string) Str::ulid(),
            'login'    => 'user2',
            'password' => Hash::make('password'),
        ]);
    }
}
