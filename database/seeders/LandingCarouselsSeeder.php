<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LandingCarousel;
use Illuminate\Database\Seeder;

class LandingCarouselsSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->first();

        LandingCarousel::query()->create([
            'image_path'  => 'img/banners/banner1.jpg',
            'category_id' => $category?->id,
            'sort_order'  => 1,
            'title'       => 'Акция на линзы',
        ]);

        LandingCarousel::query()->create([
            'image_path'  => 'img/banners/banner2.jpg',
            'category_id' => null,
            'sort_order'  => 2,
            'title'       => 'Новая коллекция очков',
        ]);
    }
}
