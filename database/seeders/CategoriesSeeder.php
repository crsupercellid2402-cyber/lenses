<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // Родительские категории
        $lenses = Category::query()->create([
            'parent_id'       => null,
            'name'            => 'Линзы',
            'name_uz'         => 'Linzalar',
            'description'     => 'Контактные линзы для зрения',
            'description_uz'  => 'Ko\'rish uchun kontakt linzalar',
            'slug'            => 'linzy',
            'is_active'       => true,
        ]);

        $glasses = Category::query()->create([
            'parent_id'       => null,
            'name'            => 'Очки',
            'name_uz'         => 'Ko\'zoynak',
            'description'     => 'Оправы и готовые очки',
            'description_uz'  => 'Ramkalar va tayyor ko\'zoynaklar',
            'slug'            => 'ochki',
            'is_active'       => true,
        ]);

        // Дочерние категории
        Category::query()->create([
            'parent_id'       => $lenses->id,
            'name'            => 'Однодневные линзы',
            'name_uz'         => 'Bir kunlik linzalar',
            'description'     => 'Линзы для однодневного ношения',
            'description_uz'  => 'Bir kunlik kiyish uchun linzalar',
            'slug'            => 'odnonevnye-linzy',
            'is_active'       => true,
        ]);

        Category::query()->create([
            'parent_id'       => $glasses->id,
            'name'            => 'Солнцезащитные очки',
            'name_uz'         => 'Quyosh ko\'zoynagi',
            'description'     => 'Очки с UV-защитой',
            'description_uz'  => 'UV-himoyali ko\'zoynak',
            'slug'            => 'solntsezashhitnye-ochki',
            'is_active'       => true,
        ]);
    }
}
