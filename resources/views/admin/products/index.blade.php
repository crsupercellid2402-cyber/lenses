@extends('admin.layouts.app')

@section('title')
    <title>Админ — Продукты</title>
@endsection

@section('content')
    <div class="flex justify-between items-center py-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-navy-50">Продукты</h1>

        <button onclick="window.location.href='{{ route('products.create') }}'"
                class="rounded-full bg-blue-600 px-6 py-2.5 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
            Добавить продукт
        </button>
    </div>

    <!-- Фильтры -->
    <div class="mb-6 bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-4">
        <form method="GET" action="{{ route('products.index') }}" class="grid sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ID или название"
                       class="w-full rounded-lg border border-slate-300 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Категория</label>
                <select name="category"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <option value="">Все</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Статус</label>
                <select name="status"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <option value="">Все</option>
                    <option value="1" @selected(request('status') === '1')>Активные</option>
                    <option value="0" @selected(request('status') === '0')>Неактивные</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                    Фильтровать
                </button>
                <a href="{{ route('products.index') }}"
                   class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300 transition">
                    Сбросить
                </a>
            </div>
        </form>
    </div>

    <!-- Таблица -->
    <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-navy-600 shadow-sm bg-white dark:bg-navy-800">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-navy-600">
            <thead class="bg-slate-100 dark:bg-navy-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">ID</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Название</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Категория</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600 dark:text-navy-100">Цена</th>
                <th class="px-4 py-3 text-center text-sm font-semibold text-slate-600 dark:text-navy-100">Статус</th>
                <th class="px-4 py-3 text-center text-sm font-semibold text-slate-600 dark:text-navy-100">Действия</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-navy-600 text-slate-700 dark:text-navy-100">
            @forelse($products as $product)
                <tr class="hover:bg-slate-50 dark:hover:bg-navy-700 transition">
                    <td class="px-4 py-3 text-sm font-medium">{{ $product->id }}</td>

                    <td class="px-4 py-3 font-semibold text-slate-800">
                        <a href="{{ route('products.show', $product->id) }}"
                           class="text-blue-600 hover:text-blue-800 font-semibold transition group relative inline-block">
                            {{ $product->name }}
                            <span class="absolute left-0 -bottom-0.5 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </td>

                    <td class="px-4 py-3 text-sm">{{ $product->category->name ?? '—' }}</td>

                    <td class="px-4 py-3 text-sm font-semibold">
                        {{ number_format($product->price, 0) }} сум
                    </td>

                    <td class="px-4 py-3 text-center">
                        @if($product->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-800 dark:text-emerald-200">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                Активен
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-rose-100 text-rose-700 dark:bg-rose-800 dark:text-rose-200">
                                <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                                Неактивен
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <!-- Просмотр -->
                            <button title="Просмотреть"
                                    onclick="window.location.href='{{ route('products.show', $product->id) }}'"
                                    class="p-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12 18 19.5 12 19.5 2.25 12 2.25 12z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 15.75A3.75 3.75 0 1012 8.25a3.75 3.75 0 000 7.5z"/>
                                </svg>
                            </button>

                            <!-- Редактировать -->
                            <button title="Редактировать"
                                    onclick="window.location.href='{{ route('products.edit', $product->id) }}'"
                                    class="p-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>

                            <!-- Удалить -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                  onsubmit="return confirm('Удалить продукт?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Удалить"
                                        class="p-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                        Ничего не найдено.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <x-pagination :paginator="$products"/>
    </div>
@endsection