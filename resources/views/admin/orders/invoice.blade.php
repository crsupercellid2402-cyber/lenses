
<div style="font-family: DejaVu Sans, sans-serif;">
    <h2 style="text-align: center; margin-bottom: 20px;">Накладная по заказу №{{ $order->id }}</h2>
    <p><b>Дата заказа:</b> {{ $order->created_at->format('d.m.Y H:i') }}</p>
    <p><b>Покупатель:</b> {{ $order->user->first_name }} {{ $order->user->second_name }} ({{ $order->user->phone }})</p>
    <p><b>Адрес доставки:</b> {{ $order->delivery_address }}</p>
    <p><b>Телефон для доставки:</b> {{ $order->delivery_phone }}</p>
    <p><b>Статус:</b> {{ $order->status_name }}</p>
    <p><b>Тип оплаты:</b> {{ $order->payment_name }}</p>
    <p><b>Статус оплаты:</b> {{ $order->payment_status_name }}</p>

    <table width="100%" border="1" cellspacing="0" cellpadding="5" style="margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Цена</th>
                <th>Кол-во</th>
                <th>Всего</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    @if (is_null($item->product_id))
                        Rx-заказ<br>
                        <small>
                            <b>OD:</b> SPH: {{ $order->rx[0]['sph'] ?? '-' }}, CYL: {{ $order->rx[0]['cyl'] ?? '-' }}, AXIS: {{ $order->rx[0]['axis'] ?? '-' }}, ADD: {{ $order->rx[0]['add'] ?? '-' }}, PRISM: {{ $order->rx[0]['prism'] ?? '-' }}<br>
                            <b>OS:</b> SPH: {{ $order->rx[1]['sph'] ?? '-' }}, CYL: {{ $order->rx[1]['cyl'] ?? '-' }}, AXIS: {{ $order->rx[1]['axis'] ?? '-' }}, ADD: {{ $order->rx[1]['add'] ?? '-' }}, PRISM: {{ $order->rx[1]['prism'] ?? '-' }}
                        </small>
                    @else
                        {{ $item->product->name ?? '—' }}
                    @endif
                </td>
                <td>{{ number_format($item->price, 0, '', ' ') }} сум</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity, 0, '', ' ') }} сум</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3 style="text-align: right; margin-top: 30px;">Итого: {{ number_format($order->total, 0, '', ' ') }} сум</h3>
</div>
