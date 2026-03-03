<?php

namespace Database\Seeders;

use App\Models\BotUser;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FavoritesSeeder extends Seeder
{
    public function run(): void
    {
        $users    = BotUser::query()->take(2)->get();
        $products = Product::query()->take(2)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        Favorite::query()->create([
            'user_id'    => $users->first()->id,
            'product_id' => $products->first()->id,
        ]);

        if ($users->count() >= 2 && $products->count() >= 2) {
            Favorite::query()->create([
                'user_id'    => $users->get(1)->id,
                'product_id' => $products->get(1)->id,
            ]);
        }
    }
}
