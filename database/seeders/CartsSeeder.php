<?php

namespace Database\Seeders;

use App\Models\BotUser;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartsSeeder extends Seeder
{
    public function run(): void
    {
        $users    = BotUser::query()->take(2)->get();
        $products = Product::query()->take(2)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($users as $index => $user) {
            $cart = Cart::query()->create([
                'user_id' => $user->id,
            ]);

            $product = $products->get($index);
            if ($product) {
                CartItem::query()->create([
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                    'price'      => $product->price,
                    'quantity'   => 2,
                    'rx_sph'     => -1.50,
                    'rx_cyl'     => -0.50,
                    'rx_axis'    => 90,
                    'rx_add'     => null,
                    'rx_prism'   => null,
                ]);
            }
        }
    }
}
