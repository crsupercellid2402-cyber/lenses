@extends('admin.layouts.app')

@section('title')
    <title>Чаты поддержки</title>
@endsection

@section('content')

    <div class="flex justify-between items-center py-6">
        <h1 class="text-2xl font-semibold">Чаты поддержки</h1>
    </div>

    <div class="overflow-hidden rounded-xl border shadow bg-white dark:bg-navy-800 dark:border-navy-600">

        <table class="min-w-full divide-y divide-slate-200 dark:divide-navy-600">
            <thead class="bg-slate-100 dark:bg-navy-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Пользователь</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Последнее сообщение</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Статус</th>
                <th class="px-4 py-3 text-center text-sm font-semibold">Дата</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-navy-600">
            @foreach($chats as $chat)
                <tr onclick="location.href='{{ route('support.show', $chat->id) }}'"
                    class="hover:bg-slate-50 dark:hover:bg-navy-700 cursor-pointer transition">

                    <td class="px-4 py-3">
                        {{ $chat->user->first_name }} {{ $chat->user->second_name }}
                    </td>

                    <td class="px-4 py-3">
                        {{ Str::limit($chat->lastMessage->text ?? '—', 60) }}
                    </td>

                    <td class="px-4 py-3">
                        @if($chat->status === 'new')
                            <span class="px-2 py-1 bg-amber-500 text-white rounded text-xs">Новый</span>
                        @elseif($chat->status === 'open')
                            <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Открыт</span>
                        @else
                            <span class="px-2 py-1 bg-slate-500 text-white rounded text-xs">Закрыт</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $chat->updated_at->format('d.m.Y H:i') }}
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $chats->links() }}
    </div>

@endsection
