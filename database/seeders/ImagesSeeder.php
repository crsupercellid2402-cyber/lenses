<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ImagesSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->get();

        foreach ($products as $product) {
            Image::query()->create([
                'url'            => 'img/products/' . $product->slug . '-1.jpg',
                'imageable_type' => Product::class,
                'imageable_id'   => $product->id,
            ]);

            Image::query()->create([
                'url'            => 'img/products/' . $product->slug . '-2.jpg',
                'imageable_type' => Product::class,
                'imageable_id'   => $product->id,
            ]);
        }
    }
}
