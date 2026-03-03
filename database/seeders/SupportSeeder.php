<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\BotUser;
use App\Models\SupportChat;
use App\Models\SupportMessage;
use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    public function run(): void
    {
        $users = BotUser::query()->take(2)->get();
        $admin = Admin::query()->first();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $index => $user) {
            $status = $index === 0 ? 'open' : 'closed';

            $chat = SupportChat::query()->create([
                'bot_user_id' => $user->id,
                'status'      => $status,
            ]);

            // Сообщение от пользователя
            SupportMessage::query()->create([
                'chat_id'      => $chat->id,
                'admin_id'     => null,
                'is_from_user' => true,
                'text'         => 'Здравствуйте, у меня вопрос по заказу.',
            ]);

            // Ответ от администратора
            SupportMessage::query()->create([
                'chat_id'      => $chat->id,
                'admin_id'     => $admin?->id,
                'is_from_user' => false,
                'text'         => 'Здравствуйте! Чем могу помочь?',
            ]);
        }
    }
}
