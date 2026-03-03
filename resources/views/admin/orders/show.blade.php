@extends('admin.layouts.app')

@section('title')
    <title>Заказ #{{ $order->id }}</title>
@endsection

@section('content')
    <div class="flex justify-between items-center py-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-navy-50">
            Заказ №{{ $order->id }}
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('orders.invoice.pdf', $order) }}"
                class="rounded-full bg-emerald-600 px-6 py-2.5 text-white font-medium hover:bg-emerald-700 transition"
                target="_blank">
                Скачать PDF-накладную
            </a>
            <a href="{{ route('orders.index') }}"
                class="rounded-full bg-slate-600 px-6 py-2.5 text-white font-medium hover:bg-slate-700 transition">
                ← Назад
            </a>
        </div>
    </div>

    <!-- Основная информация -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- 🧾 Карточка: Информация о заказе -->
        <div class="bg-white dark:bg-navy-800 rounded-xl border border-slate-200 dark:border-navy-600 p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-navy-50">Информация о заказе</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">ID заказа:</span>
                    <span class="font-medium">{{ $order->id }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Дата создания:</span>
                    <span class="font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Статус заказа:</span>

                    <div class="flex items-center gap-2">
                        <select id="orderStatusSelect"
                            class="border border-slate-300 dark:border-navy-500 rounded-lg px-3 py-1.5 text-sm font-medium bg-white dark:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>Новый</option>
                            <option value="in_process" {{ $order->status === 'in_process' ? 'selected' : '' }}>В обработке
                            </option>
                            <option value="done" {{ $order->status === 'done' ? 'selected' : '' }}>Завершён</option>
                            <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Отменён</option>
                        </select>

                        <button id="updateStatusBtn" disabled
                            class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Сохранить
                        </button>
                    </div>
                </div>

                <div id="statusMessage" class="hidden text-sm rounded-lg p-3 mt-2"></div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Сумма:</span>
                    <span class="font-semibold text-lg">
                        {{ number_format($order->total, 0, '', ' ') }} сум
                    </span>
                </div>
            </div>
        </div>

        <!-- 👤 Карточка: Информация о клиенте -->
        <div class="bg-white dark:bg-navy-800 rounded-xl border border-slate-200 dark:border-navy-600 p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-navy-50">Покупатель</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Имя:</span>
                    <span class="font-medium">{{ $order->user->first_name ?? '—' }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Фамилия:</span>
                    <span class="font-medium">{{ $order->user->second_name ?? '—' }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Телефон:</span>
                    <span class="font-medium">{{ $order->user->phone ?? '—' }}</span>
                </div>
            </div>
        </div>

        <!-- 💳 Карточка: Оплата -->
        <div class="bg-white dark:bg-navy-800 rounded-xl border border-slate-200 dark:border-navy-600 p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-navy-50">Оплата</h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Тип оплаты:</span>
                    <span class="font-medium text-slate-800 dark:text-navy-100">
                        {{ strtoupper($order->payment_type) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Статус:</span>

                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $order->payment_status === 'paid'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-800 dark:text-emerald-200'
                            : 'bg-slate-200 text-slate-600 dark:bg-navy-600 dark:text-slate-300' }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- 🛒 Товары заказа -->
    <div class="mt-10 bg-white dark:bg-navy-800 rounded-xl border border-slate-200 dark:border-navy-600 shadow-sm">
        <div class="p-6 border-b border-slate-200 dark:border-navy-600">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-navy-50">Товары</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-navy-600">
                <thead class="bg-slate-100 dark:bg-navy-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Название</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Цена</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Кол-во</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Всего</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-navy-600">
                    @foreach ($order->items as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-navy-700 transition">
                            <td class="px-4 py-3 text-sm">{{ $item->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium">
                                @if (is_null($item->product_id))
                                    <span class="text-blue-700">Rx-заказ</span>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <b>SPH:</b> {{ $item->rx_sph ?? '-' }},
                                        <b>CYL:</b> {{ $item->rx_cyl ?? '-' }},
                                        <b>AXIS:</b> {{ $item->rx_axis ?? '-' }},
                                        <b>ADD:</b> {{ $item->rx_add ?? '-' }},
                                        <b>PRISM:</b> {{ $item->rx_prism ?? '-' }}
                                    </div>
                                @else
                                    {{ $item->product->name ?? '—' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ number_format($item->price, 0, '', ' ') }} сум
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold">
                                {{ number_format($item->price * $item->quantity, 0, '', ' ') }} сум
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('orderStatusSelect');
            const updateBtn = document.getElementById('updateStatusBtn');
            const statusMessage = document.getElementById('statusMessage');
            const orderId = {{ $order->id }};
            let initialStatus = statusSelect.value;

            // Проверяем, изменился ли статус
            statusSelect.addEventListener('change', function() {
                updateBtn.disabled = statusSelect.value === initialStatus;
            });

            updateBtn.addEventListener('click', function() {
                const newStatus = statusSelect.value;

                console.log('Отправка запроса на изменение статуса:', {
                    orderId: orderId,
                    newStatus: newStatus,
                    url: `/dashboard/orders/${orderId}/status`
                });

                // Подтверждение при отмене заказа
                if (newStatus === 'canceled' && !confirm(
                        'Вы уверены, что хотите отменить заказ? Товары будут возвращены на склад.')) {
                    return;
                }

                updateBtn.disabled = true;
                updateBtn.textContent = 'Сохранение...';
                statusMessage.classList.add('hidden');

                fetch(`/dashboard/orders/${orderId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => {
                        console.log('Получен ответ:', response.status, response.statusText);
                        if (!response.ok) {
                            return response.json().then(err => {
                                console.error('Ошибка от сервера:', err);
                                throw new Error(err.message || 'Ошибка сервера');
                            }).catch(() => {
                                throw new Error(
                                    `HTTP ${response.status}: ${response.statusText}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Данные от сервера:', data);
                        if (data.success) {
                            statusMessage.className =
                                'text-sm rounded-lg p-3 mt-2 bg-emerald-100 text-emerald-700 dark:bg-emerald-800 dark:text-emerald-200';
                            statusMessage.textContent =
                                `✓ Статус успешно изменен на "${data.statusName}"`;
                            statusMessage.classList.remove('hidden');

                            // Обновляем начальное значение
                            initialStatus = newStatus;
                            updateBtn.disabled = true;

                            // Перезагружаем страницу через 1.5 секунды
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            throw new Error('Ошибка обновления статуса');
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при обновлении статуса:', error);
                        statusMessage.className =
                            'text-sm rounded-lg p-3 mt-2 bg-rose-100 text-rose-700 dark:bg-rose-800 dark:text-rose-200';
                        statusMessage.textContent =
                            `✗ Ошибка: ${error.message || 'Произошла ошибка при обновлении статуса'}`;
                        statusMessage.classList.remove('hidden');
                        updateBtn.disabled = false;
                    })
                    .finally(() => {
                        updateBtn.textContent = 'Сохранить';
                    });
            });
        });
    </script>
@endsection
