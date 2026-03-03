@extends('admin.layouts.app')

@section('title')
    <title>Категории</title>
@endsection

@section('content')
    <div class="flex justify-between items-center py-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-navy-50">Категории</h1>

        <button onclick="window.location.href='{{ route('categories.create') }}'"
            class="rounded-full bg-blue-600 px-6 py-2.5 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
            Добавить категорию
        </button>
    </div>

    <!-- 📊 Статистика -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-4">
            <div class="text-sm text-slate-500 ">Всего категорий</div>
            <div class="text-2xl font-semibold text-slate-800  mt-1">
                {{ $stats['total'] ?? $categories->total() }}
            </div>
        </div>

        <div
            class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl shadow-sm border border-emerald-200 dark:border-emerald-700 p-4">
            <div class="text-sm text-emerald-600">Активные</div>
            <div class="text-2xl font-semibold text-emerald-700  mt-1">
                {{ $stats['active'] ?? $categories->where('is_active', true)->count() }}
            </div>
        </div>

        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-xl shadow-sm border dark:border-rose-400 p-4">
            <div class="text-sm text-rose-600 dark:text-rose-400">Неактивные</div>
            <div class="text-2xl font-semibold text-rose-700  mt-1">
                {{ $stats['inactive'] ?? $categories->where('is_active', false)->count() }}
            </div>
        </div>
    </div>

    <!-- Таблица -->
    <div
        class="overflow-hidden rounded-xl border border-slate-200 dark:border-navy-600 shadow-sm bg-white dark:bg-navy-800">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-navy-600">
            <thead class="bg-slate-100 dark:bg-navy-700">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Название</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Путь</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Название UZ</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Описание</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Описание UZ</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-600 dark:text-navy-100">Скидка (%)
                    </th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-600 dark:text-navy-100">Статус</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-600 dark:text-navy-100">Действия</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-navy-600 text-slate-700 dark:text-navy-100">
                @foreach ($categories as $category)
                    <tr class="hover:bg-slate-50 dark:hover:bg-navy-700 transition">
                        <td class="px-4 py-3 text-sm font-medium">{{ $category->id }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-800 ">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $category->slug }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-800 ">{{ $category->name_uz }}</td>
                        <td class="px-4 py-3 text-sm">{{ $category->description }}</td>
                        <td class="px-4 py-3 text-sm">{{ $category->description_uz }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($category->discount_percent !== null)
                                <span
                                    class="inline-block px-2 py-1 text-xs rounded bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $category->discount_percent }}%
                                </span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($category->is_active)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-800 dark:text-emerald-200">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                    Активна
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-rose-100 text-rose-700 dark:bg-rose-800 dark:text-rose-200">
                                    <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                                    Неактивна
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <!-- Редактировать -->
                                <button title="Редактировать"
                                    onclick="window.location.href='{{ route('categories.edit', $category->id) }}'"
                                    class="p-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <!-- Удалить -->
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Удалить категорию?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Удалить"
                                        class="p-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <x-pagination :paginator="$categories" />
    </div>
@endsection
