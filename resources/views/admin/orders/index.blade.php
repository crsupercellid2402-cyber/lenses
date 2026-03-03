@extends('admin.layouts.app')

@section('title')
    <title>Админ — Заказы</title>
@endsection

@section('content')
    <div class="flex justify-between items-center py-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-navy-50">Заказы</h1>

        <button onclick="window.location.href='{{ route('orders.export') }}'"
                class="rounded-full bg-emerald-600 px-6 py-2.5 text-white font-medium hover:bg-emerald-700 transition">
            Экспорт в Excel
        </button>
    </div>

    <div class="mb-6 bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-4">
        <form method="GET" action="{{ route('orders.index') }}" class="grid sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700  mb-1">Поиск</label>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="ID, телефон, адрес, клиент"
                       class="w-full rounded-lg border border-slate-300  bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800  text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700  mb-1">Дата от</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                       class="w-full rounded-lg border border-slate-300  bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800  text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700  mb-1">Дата до</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                       class="w-full rounded-lg border border-slate-300  bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800  text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                    Фильтровать
                </button>
                <a href="{{ route('orders.index') }}"
                   class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300 transition">
                    Сбросить
                </a>
            </div>
        </form>
    </div>


    <!-- 📊 Статистика -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-4">
            <div class="text-sm text-slate-500">Всего заказов</div>
            <div class="text-2xl font-semibold mt-1 text-slate-800 dark:text-navy-50">
                {{ $orders->count() }}
            </div>
        </div>

        <div class="bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-4">
            <div class="text-sm text-slate-500">Новые</div>
            <div class="text-2xl font-semibold mt-1 text-blue-600">
                {{ $orders->where('status', 'new')->count() }}
            </div>
        </div>

        <div class="bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-4">
            <div class="text-sm text-slate-500">В процессе</div>
            <div class="text-2xl font-semibold mt-1 text-amber-600">
                {{ $orders->where('status', 'in_process')->count() }}
            </div>
        </div>

        <div class="bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-4">
            <div class="text-sm text-slate-500">Завершено</div>
            <div class="text-2xl font-semibold mt-1 text-emerald-600">
                {{ $orders->where('status', 'done')->count() }}
            </div>
        </div>
    </div>

    <!-- 🧾 Таблица -->
    <div
        class="overflow-hidden rounded-xl border border-slate-200 dark:border-navy-600 shadow-sm bg-white dark:bg-navy-800">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-navy-600 text-sm">
            <thead class="bg-slate-100 dark:bg-navy-700">
            <tr class="text-left">
                <th class="px-3 py-2">ID</th>
                <th class="px-3 py-2">Пользователь</th>
                <th class="px-3 py-2">Телефон доставки</th>
                <th class="px-3 py-2">Адрес доставки</th>
                <th class="px-3 py-2">Тип доставки</th>
                <th class="px-3 py-2">Сумма</th>
                <th class="px-3 py-2">Тип оплаты</th>
                <th class="px-3 py-2">Статус оплаты</th>
                <th class="px-3 py-2">Статус заказа</th>
                <th class="px-3 py-2">Создан</th>
                <th class="px-3 py-2 text-center">Действия</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-navy-600">
            @foreach($orders as $order)
                <tr class="hover:bg-slate-50 dark:hover:bg-navy-700 transition">

                    <td class="px-3 py-2 font-medium">{{ $order->id }}</td>

                    <td class="px-3 py-2">
                        {{ $order->user->first_name ?? '—' }}
                        {{ $order->user->second_name ?? '' }}
                        <div class="text-xs text-slate-400">{{ $order->user->phone ?? '' }}</div>
                    </td>

                    <td class="px-3 py-2">{{ $order->delivery_phone }}</td>

                    <td class="px-3 py-2">{{ $order->delivery_address }}</td>

                    <td class="px-3 py-2">{{ $order->delivery_type }}</td>

                    <td class="px-3 py-2 font-semibold">
                        {{ number_format($order->total, 0, '', ' ') }} сум
                    </td>

                    <td class="px-3 py-2">
                        {{ $order->getPaymentNameAttribute() }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $order->getPaymentStatusNameAttribute() }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $order->getStatusNameAttribute() }}
                    </td>

                    <td class="px-3 py-2 text-xs text-slate-500">
                        {{ $order->created_at?->format('d.m.Y H:i') }}
                    </td>

                    <td class="px-3 py-2 text-center">
                        <button
                            onclick="window.location.href='{{ route('orders.show', $order->id) }}'"
                            class="px-3 py-1 rounded bg-emerald-600 text-white hover:bg-emerald-700">
                            Просмотр
                        </button>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <script>
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function () {

                const orderId = this.dataset.id;
                const newStatus = this.value;
                const self = this;

                fetch(`/dashboard/orders/${orderId}/status`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({status: newStatus})
                })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) return;

                        // Обновляем цвет бейджа
                        const colors = {
                            new: "bg-blue-100 text-blue-700",
                            in_process: "bg-amber-100 text-amber-700",
                            done: "bg-emerald-100 text-emerald-700",
                            canceled: "bg-rose-100 text-rose-700",
                        };

                        self.className = "status-select px-2 py-1 rounded-lg text-xs font-medium " + colors[newStatus];
                    });
            });
        });
    </script>

@endsection
