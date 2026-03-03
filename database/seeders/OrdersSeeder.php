<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BotUser;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
     public function run(): void
     {
          // Получаем случайного пользователя или создаём тестового
          $user = BotUser::query()->inRandomOrder()->first();
          if (!$user) {
               $user = BotUser::create([
                    'chat_id' => rand(100000, 999999),
                    'first_name' => 'Test',
                    'second_name' => 'User',
                    'uname' => 'testuser',
                    'phone' => '+998901234567',
                    'is_active' => true,
               ]);
          }

          // Получаем случайные продукты из БД
          $products = Product::query()->inRandomOrder()->take(10)->get();
          if ($products->isEmpty()) {
               throw new \Exception('Нет продуктов в базе данных.');
          }

          // Массив статусов для разнообразия
          $statuses = [
               Order::STATUS_NEW,
               Order::STATUS_PROCESS,
               Order::STATUS_DONE,
               Order::STATUS_CANCELED,
          ];
          $paymentTypes = [Order::PAYMENT_CASH, Order::PAYMENT_PAYME];
          $paymentStatuses = [Order::PAYMENT_PENDING, Order::PAYMENT_PAID, Order::PAYMENT_FAILED];

          for ($i = 1; $i <= 10; $i++) {
               $status = $statuses[($i - 1) % count($statuses)];
               $paymentType = $paymentTypes[array_rand($paymentTypes)];
               $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

               $order = Order::create([
                    'user_id' => $user->id,
                    'total' => 0, // пересчитаем ниже
                    'status' => $status,
                    'payment_type' => $paymentType,
                    'payment_status' => $paymentStatus,
                    'delivery_type' => 'pickup',
                    'delivery_address' => 'Test Address ' . $i,
                    'delivery_phone' => $user->phone,
               ]);

               $total = 0;
               // Добавляем 1-3 случайных товара в заказ
               $maxItems = min(3, $products->count());
               $items = $products->random(rand(1, $maxItems));
               foreach ($items as $product) {
                    $quantity = rand(1, 5);
                    OrderItem::create([
                         'order_id' => $order->id,
                         'product_id' => $product->id,
                         'price' => $product->price,
                         'quantity' => $quantity,
                    ]);
                    $total += $product->price * $quantity;
               }
               $order->update(['total' => $total]);
          }
     }
}
