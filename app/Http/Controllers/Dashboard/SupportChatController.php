<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\SupportChat;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class SupportChatController
{
    public function index(): View|Factory|Application
    {
        $chats = SupportChat::query()->with(['user', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.support.index', compact('chats'));
    }

    public function show(SupportChat $chat): View|Factory|Application
    {
        $chat->load(['user', 'messages.admin']);

        if ($chat->status === 'new') {
            $chat->update(['status' => 'open']);
        }

        return view('admin.support.show', compact('chat'));
    }
}
