<?php

namespace Database\Seeders;

use App\Models\BotUser;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $users    = BotUser::query()->take(2)->get();
        $products = Product::query()->take(2)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        Review::query()->create([
            'product_id' => $products->first()->id,
            'user_id'    => $users->first()->id,
            'rating'     => 5,
            'text'       => 'Отличные линзы, очень комфортные!',
        ]);

        Review::query()->create([
            'product_id' => $products->get(1) ? $products->get(1)->id : $products->first()->id,
            'user_id'    => $users->count() >= 2 ? $users->get(1)->id : $users->first()->id,
            'rating'     => 4,
            'text'       => 'Хорошее качество, буду покупать снова.',
        ]);
    }
}
