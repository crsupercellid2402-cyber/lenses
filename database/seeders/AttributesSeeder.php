<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->first();

        // Атрибуты
        $colorAttr = Attribute::query()->create([
            'name'        => 'Цвет',
            'type'        => 'string',
            'category_id' => $category?->id,
        ]);

        $coatingAttr = Attribute::query()->create([
            'name'        => 'Покрытие',
            'type'        => 'string',
            'category_id' => $category?->id,
        ]);

        // Значения атрибутов
        AttributeValue::query()->create(['attribute_id' => $colorAttr->id, 'value' => 'Прозрачный']);
        AttributeValue::query()->create(['attribute_id' => $colorAttr->id, 'value' => 'Голубой']);
        AttributeValue::query()->create(['attribute_id' => $coatingAttr->id, 'value' => 'Антибликовое']);
        AttributeValue::query()->create(['attribute_id' => $coatingAttr->id, 'value' => 'UV-защита']);

        // Атрибуты продуктов
        $products = Product::query()->take(2)->get();

        if ($products->count() >= 1) {
            ProductAttribute::query()->create([
                'product_id'   => $products->first()->id,
                'attribute_id' => $colorAttr->id,
                'value'        => 'Прозрачный',
            ]);
        }

        if ($products->count() >= 2) {
            ProductAttribute::query()->create([
                'product_id'   => $products->get(1)->id,
                'attribute_id' => $coatingAttr->id,
                'value'        => 'Антибликовое',
            ]);
        }
    }
}
