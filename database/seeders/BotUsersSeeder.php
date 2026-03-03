<?php

namespace Database\Seeders;

use App\Models\BotUser;
use Illuminate\Database\Seeder;

class BotUsersSeeder extends Seeder
{
    public function run(): void
    {
        BotUser::query()->create([
            'chat_id'     => 100000001,
            'first_name'  => 'Алексей',
            'second_name' => 'Иванов',
            'uname'       => 'alexivanov',
            'phone'       => '+998901234567',
            'is_active'   => true,
            'lang'        => 'ru',
        ]);

        BotUser::query()->create([
            'chat_id'     => 100000002,
            'first_name'  => 'Мария',
            'second_name' => 'Петрова',
            'uname'       => 'mpetrova',
            'phone'       => '+998907654321',
            'is_active'   => true,
            'lang'        => 'uz',
        ]);
    }
}
