<?php

namespace App\Http\Controllers\Telegram;

use App\Models\BotUser;
use App\Models\SupportChat;
use App\Models\SupportMessage;
use Throwable;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramController
{
    protected Api $telegram;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    private function t($user, $key, $replace = [])
    {
        app()->setLocale($user->lang ?? 'ru');

        return __($key, $replace);
    }

    private function showLangMenu($chatId, $user = null)
    {
        if ($user) {
            app()->setLocale($user->lang ?? 'ru');
        }

        $keyboard = Keyboard::make([
            'keyboard' => [
                [['text' => '🇷🇺 Русский']],
                [['text' => '🇺🇿 O‘zbek']],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $text = $user
            ? $this->t($user, 'bot.change_language')
            : __('bot.choose_language');

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => $keyboard,
        ]);
    }

    public function handleWebhook(): void
    {
        $update = $this->telegram->getWebhookUpdates();
        $this->telegram->commandsHandler(true);

        if (! $update->has('message')) {
            return;
        }

        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();
        $text = trim((string) $message->getText());
        if ($text === '') {
            $caption = trim((string) data_get($message, 'caption', ''));
            if ($caption !== '') {
                $text = $caption;
            }
        }
        $username = $message->getChat()->getUsername();

        $user = BotUser::firstOrCreate(
            ['chat_id' => $chatId],
            [
                'uname' => $username,
                'step' => 'choose_lang',
                'lang' => null,
                'is_active' => true,
            ]
        );

        if (! $user->is_active) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.account_inactive', ['admin' => '@exampleAdmin']),
            ]);

            return;
        }

        /**
         * ===========================
         * ВЫБОР ЯЗЫКА ПРИ ВХОДЕ
         * ===========================
         */
        if ($text === '/start') {
            $user->update(['step' => 'choose_lang']);
            $this->showLangMenu($chatId);

            return;

        }

        if ($user->step === 'choose_lang') {

            if ($text === '🇷🇺 Русский') {
                $user->update(['lang' => 'ru', 'step' => 'ask_first_name']);
            }

            if ($text === '🇺🇿 O‘zbek') {
                $user->update(['lang' => 'uz', 'step' => 'ask_first_name']);
            }

            if (! $user->lang) {
                $this->showLangMenu($chatId);

                return;
            }

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.language_selected'),
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.welcome'),
            ]);

            $user->update(['step' => 'ask_first_name']);

            return;
        }

        /**
         * ===========================
         * РЕГИСТРАЦИЯ
         * ===========================
         */
        if ($user->step === 'ask_first_name') {

            $user->update([
                'first_name' => $text,
                'step' => 'ask_second_name',
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.enter_lastname'),
            ]);

            return;
        }

        if ($user->step === 'ask_second_name') {

            $user->update([
                'second_name' => $text,
                'step' => 'ask_phone',
            ]);

            $keyboard = Keyboard::make([
                'keyboard' => [
                    [
                        [
                            'text' => $this->t($user, 'bot.send_phone_button'),
                            'request_contact' => true,
                        ],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.send_phone'),
                'reply_markup' => $keyboard,
            ]);

            return;
        }

        if ($message->has('contact') && $user->step === 'ask_phone') {

            $phone = $message->getContact()->getPhoneNumber();

            $user->update([
                'phone' => $phone,
                'step' => 'done',
            ]);

            $this->sendMainMenu($chatId, $user);

            return;
        }

        if ($user->step === 'ask_phone') {

            $clean = preg_replace('/\D/', '', $text);

            if (! is_numeric($clean)) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $this->t($user, 'bot.phone_invalid'),
                ]);

                return;
            }

            $user->update([
                'phone' => $clean,
                'step' => 'done',
            ]);

            $this->sendMainMenu($chatId, $user);

            return;
        }

        /**
         * ===========================
         * ИЗМЕНЕНИЕ ЯЗЫКА В НАСТРОЙКАХ
         * ===========================
         */
        // Изменить имя
        if ($user->step === 'profile_menu' && $text === $this->t($user, 'bot.menu.edit_first')) {
            $user->update(['step' => 'edit_first']);
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.enter_new_first'),
            ]);

            return;
        }

        // Изменить фамилию
        if ($user->step === 'profile_menu' && $text === $this->t($user, 'bot.menu.edit_last')) {
            $user->update(['step' => 'edit_last']);
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.enter_new_last'),
            ]);

            return;
        }

        // Изменить телефон
        if ($user->step === 'profile_menu' && $text === $this->t($user, 'bot.menu.edit_phone')) {
            $user->update(['step' => 'edit_phone']);

            $keyboard = Keyboard::make([
                'keyboard' => [
                    [
                        [
                            'text' => $this->t($user, 'bot.send_phone_button'),
                            'request_contact' => true,
                        ],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.send_phone'),
                'reply_markup' => $keyboard,
            ]);

            return;
        }

        if ($user->step === 'edit_first') {
            $user->update([
                'first_name' => $text,
                'step' => 'profile_menu',
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.data_updated'),
            ]);

            $this->showProfileMenu($chatId, $user);

            return;

        }

        if ($user->step === 'edit_last') {
            $user->update([
                'second_name' => $text,
                'step' => 'profile_menu',
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.data_updated'),
            ]);

            $this->showProfileMenu($chatId, $user);

            return;
        }

        if ($message->has('contact') && $user->step === 'edit_phone') {
            $phone = $message->getContact()->getPhoneNumber();

            $user->update([
                'phone' => $phone,
                'step' => 'profile_menu',
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.data_updated'),
            ]);

            $this->showProfileMenu($chatId, $user);

            return;
        }

        if ($message->has('contact') && $user->step === 'edit_phone') {
            $phone = $message->getContact()->getPhoneNumber();

            $user->update([
                'phone' => $phone,
                'step' => 'profile_menu',
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.data_updated'),
            ]);

            $this->showProfileMenu($chatId, $user);

            return;
        }

        if ($user->step === 'change_lang') {

            $newLang = null;
            if ($text === '🇷🇺 Русский') {
                $newLang = 'ru';
            } elseif ($text === '🇺🇿 O‘zbek') {
                $newLang = 'uz';
            }

            if ($newLang) {
                $user->update(['lang' => $newLang, 'step' => 'profile_menu']);
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $this->t($user, 'bot.language_changed'),
                ]);
                $this->showProfileMenu($chatId, $user);

                return;
            } else {
                $this->showLangMenu($chatId, $user);

                return;
            }
        }

        // ✅ КНОПКА "ИЗМЕНИТЬ ЯЗЫК"
        if ($user->step === 'profile_menu' && $text === $this->t($user, 'bot.menu.change_language')) {
            $user->update(['step' => 'change_lang']);
            $this->showLangMenu($chatId, $user);

            return;
        }

        /**
         * ===========================
         * МЕНЮ
         * ===========================
         */
        if ($text === $this->t($user, 'bot.menu.orders')) {
            $this->sendOrdersButton($chatId, $user);

            return;
        }

        if ($text === $this->t($user, 'bot.menu.profile')) {

            $this->showProfileMenu($chatId, $user);
            $user->update(['step' => 'profile_menu']);

            return;
        }

        if ($text === $this->t($user, 'bot.menu.shop')) {
            $this->sendShopButton($chatId, $user);

            return;
        }

        if ($text === $this->t($user, 'bot.menu.back')) {
            $user->update(['step' => 'done']);
            $this->sendMainMenu($chatId, $user);

            return;
        }

        /**
         * ===========================
         * ЧАТ С МЕНЕДЖЕРОМ
         * ===========================
         */
        if ($text === $this->t($user, 'bot.menu.manager')) {

            $chat = SupportChat::firstOrCreate(
                ['bot_user_id' => $user->id],
                ['status' => 'new']
            );

            $user->update(['step' => 'chat_with_manager_first_message']);

            $keyboard = Keyboard::make([
                'keyboard' => [
                    [['text' => $this->t($user, 'bot.end_chat')]],
                ],
                'resize_keyboard' => true,
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.chat_connected'),
                'reply_markup' => $keyboard,
            ]);

            return;
        }

        if ($text === $this->t($user, 'bot.end_chat')) {

            $user->update(['step' => 'done']);

            $chat = SupportChat::where('bot_user_id', $user->id)->first();
            if ($chat) {
                $chat->update(['status' => 'closed']);
            }

            $this->sendMainMenu($chatId, $user);

            return;
        }

        if ($user->step === 'chat_with_manager_first_message' || $user->step === 'chat_with_manager') {

            $chat = SupportChat::firstOrCreate(
                ['bot_user_id' => $user->id],
                ['status' => 'open']
            );

            $photoUrl = $this->extractPhotoUrl($message);
            $messageText = $text !== '' ? $text : ($photoUrl ? '📷 Фото' : '');

            if ($messageText === '' && ! $photoUrl) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $this->t($user, 'bot.unknown'),
                ]);

                return;
            }

            SupportMessage::create([
                'chat_id' => $chat->id,
                'admin_id' => null,
                'is_from_user' => true,
                'text' => $messageText,
                'photo_url' => $photoUrl,
            ]);

            $chat->update(['status' => 'open']);
            $user->update(['step' => 'chat_with_manager']);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $this->t($user, 'bot.message_accepted'),
            ]);

            return;
        }

        /**
         * НЕИЗВЕСТНЫЙ ТЕКСТ
         */
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $this->t($user, 'bot.unknown'),
        ]);
    }

    /**
     * ===========================
     * ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ
     * ===========================
     */
    private function sendMainMenu($chatId, $user)
    {
        $menu = Keyboard::make([
            'keyboard' => [
                [['text' => $this->t($user, 'bot.menu.orders')]],
                [['text' => $this->t($user, 'bot.menu.profile')]],
                [['text' => $this->t($user, 'bot.menu.manager')]],
                [['text' => $this->t($user, 'bot.menu.shop')]],
            ],
            'resize_keyboard' => true,
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $this->t($user, 'bot.thanks', ['name' => $user->first_name]),
            'reply_markup' => $menu,
        ]);
    }

    private function sendOrdersButton($chatId, $user)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $this->t($user, 'bot.opening_orders'),
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        [
                            'text' => $this->t($user, 'bot.my_orders'),
                            'web_app' => [
                                'url' => env('WEBAPP_URL')."/telegram/webapp/profile?chat_id=$chatId",
                            ],
                        ],
                    ],
                ],
            ]),
        ]);
    }

    private function showProfileMenu($chatId, $user)
    {
        $text = $this->t($user, 'bot.profile', [
            'first' => $user->first_name,
            'last' => $user->second_name,
            'phone' => $user->phone,
            'lang' => strtoupper($user->lang),
        ]);

        $keyboard = Keyboard::make([
            'keyboard' => [
                [['text' => $this->t($user, 'bot.menu.edit_first')]],
                [['text' => $this->t($user, 'bot.menu.edit_last')]],
                [['text' => $this->t($user, 'bot.menu.edit_phone')]],
                [['text' => $this->t($user, 'bot.menu.change_language')]],
                [['text' => $this->t($user, 'bot.menu.back')]],
            ],
            'resize_keyboard' => true,
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => $keyboard,
        ]);
    }

    private function sendShopButton($chatId, $user)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $this->t($user, 'bot.opening_shop'),
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        [
                            'text' => $this->t($user, 'bot.go_to_shop'),
                            'web_app' => [
                                'url' => env('WEBAPP_URL')."/telegram/webapp?chat_id=$chatId",
                            ],
                        ],
                    ],
                ],
            ]),
        ]);
    }

    private function extractPhotoUrl($message): ?string
    {
        $photos = [];

        if (is_object($message) && method_exists($message, 'getPhoto')) {
            $photos = $message->getPhoto() ?? [];
        }

        if (empty($photos) && is_object($message) && method_exists($message, 'get')) {
            $photos = $message->get('photo') ?? [];
        }

        if (empty($photos)) {
            $photos = data_get($message, 'photo', []);
        }

        if ($photos instanceof \Traversable) {
            $photos = iterator_to_array($photos);
        }

        if (! is_array($photos) || empty($photos)) {
            return null;
        }

        usort($photos, function ($left, $right) {
            $leftSize = (int) data_get($left, 'file_size', 0);
            $rightSize = (int) data_get($right, 'file_size', 0);

            if (is_object($left) && method_exists($left, 'getFileSize')) {
                $leftSize = (int) $left->getFileSize();
            }

            if (is_object($right) && method_exists($right, 'getFileSize')) {
                $rightSize = (int) $right->getFileSize();
            }

            return $rightSize <=> $leftSize;
        });

        $largestPhoto = $photos[0] ?? null;
        $fileId = data_get($largestPhoto, 'file_id');

        if (! $fileId && is_object($largestPhoto) && method_exists($largestPhoto, 'getFileId')) {
            $fileId = $largestPhoto->getFileId();
        }

        if (! $fileId) {
            return null;
        }

        try {
            $file = $this->telegram->getFile([
                'file_id' => $fileId,
            ]);

            $filePath = data_get($file, 'file_path');

            if (! $filePath && is_object($file) && method_exists($file, 'getFilePath')) {
                $filePath = $file->getFilePath();
            }

            if (! $filePath) {
                return null;
            }

            return sprintf('https://api.telegram.org/file/bot%s/%s', env('TELEGRAM_BOT_TOKEN'), $filePath);
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }
}
