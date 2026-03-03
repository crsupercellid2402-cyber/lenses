@extends('admin.layouts.app')

@section('title')
    <title>Чат: {{ $chat->user->first_name }}</title>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- CHAT -->
        <div class="col-span-2">

            <div class="rounded-xl border shadow bg-white dark:bg-navy-800 dark:border-navy-600 h-[70vh] flex flex-col">

                <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                    @foreach($chat->messages as $msg)
                        @include('admin.support._message', ['msg' => $msg])
                    @endforeach
                </div>

                @if($chat->status !== 'closed')
                    <form method="POST"
                          action="{{ route('support.send', $chat->id) }}"
                          class="flex p-4 border-t dark:border-navy-600">
                        @csrf
                        <input name="text"
                               required
                               placeholder="Написать..."
                               class="flex-1 rounded-lg border p-3 dark:bg-navy-700 dark:border-navy-600">

                        <button class="ml-3 px-5 bg-emerald-600 text-white rounded-lg">
                            ➤
                        </button>
                    </form>
                @endif
            </div>

            @if($chat->status !== 'closed')
                <form method="POST" action="{{ route('support.close', $chat->id) }}" class="mt-3">
                    @csrf
                    <button class="px-5 py-2 bg-red-600 text-white rounded-lg">Закрыть чат</button>
                </form>
            @endif

        </div>

        <!-- USER INFO -->
        <div class="col-span-1">
            <div class="rounded-xl border p-5 dark:border-navy-600 bg-white dark:bg-navy-800">

                <h2 class="text-xl font-semibold mb-4">Пользователь</h2>

                <p><strong>Имя:</strong> {{ $chat->user->first_name }}</p>
                <p><strong>Фамилия:</strong> {{ $chat->user->second_name }}</p>
                <p><strong>Язык:</strong> {{ $chat->user->lang }}</p>
                <p><strong>Телефон:</strong> {{ $chat->user->phone }}</p>
                <p><strong>Telegram ID:</strong> {{ $chat->user->chat_id }}</p>
            </div>
        </div>

    </div>

@endsection
