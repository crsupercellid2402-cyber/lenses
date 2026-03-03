<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->first();

        Product::query()->create([
            'name'            => 'Acuvue Oasys',
            'name_uz'         => 'Acuvue Oasys',
            'description'     => 'Двухнедельные контактные линзы с технологией HydraLuxe.',
            'description_uz'  => 'HydraLuxe texnologiyasi bilan ikki haftalik kontakt linzalar.',
            'slug'            => 'acuvue-oasys',
            'category_id'     => $category->id,
            'price'           => 89000.00,
            'discount_percent'=> 10,
            'is_active'       => true,
            'manufacturer'    => 'Johnson & Johnson',
            'article'         => 'AO-1234',
            'model'           => 'Oasys',
            'coating'         => 'HydraLuxe',
            'index'           => 1.46,
            'sph'             => -2.50,
            'cyl'             => -0.75,
            'axis'            => 180,
            'family'          => 'Acuvue',
            'color'           => null,
            'option'          => 'Прозрачные',
        ]);

        Product::query()->create([
            'name'            => 'Air Optix Plus HydraGlyde',
            'name_uz'         => 'Air Optix Plus HydraGlyde',
            'description'     => 'Ежемесячные силикон-гидрогелевые линзы.',
            'description_uz'  => 'Oylik silikon-gidrogel linzalar.',
            'slug'            => 'air-optix-plus-hydraglyde',
            'category_id'     => $category->id,
            'price'           => 120000.00,
            'discount_percent'=> null,
            'is_active'       => true,
            'manufacturer'    => 'Alcon',
            'article'         => 'AO-5678',
            'model'           => 'HydraGlyde',
            'coating'         => 'HydraGlyde',
            'index'           => 1.48,
            'sph'             => -1.00,
            'cyl'             => null,
            'axis'            => null,
            'family'          => 'Air Optix',
            'color'           => null,
            'option'          => 'Прозрачные',
        ]);

        Product::query()->create([
            'name'            => 'Biofinity Toric',
            'name_uz'         => 'Biofinity Toric',
            'description'     => 'Ежемесячные торические линзы для астигматизма.',
            'description_uz'  => 'Astigmatizm uchun oylik torik linzalar.',
            'slug'            => 'biofinity-toric',
            'category_id'     => $category->id,
            'price'           => 145000.00,
            'discount_percent'=> 5,
            'is_active'       => true,
            'manufacturer'    => 'CooperVision',
            'article'         => 'BT-0012',
            'model'           => 'Biofinity Toric',
            'coating'         => 'Aquaform',
            'index'           => 1.43,
            'sph'             => -3.00,
            'cyl'             => -1.25,
            'axis'            => 90,
            'family'          => 'Biofinity',
            'color'           => null,
            'option'          => 'Торические',
        ]);

        Product::query()->create([
            'name'            => 'Dailies Total 1',
            'name_uz'         => 'Dailies Total 1',
            'description'     => 'Однодневные линзы с водоградиентным покрытием.',
            'description_uz'  => 'Suv gradiyenti qoplamasi bilan bir kunlik linzalar.',
            'slug'            => 'dailies-total-1',
            'category_id'     => $category->id,
            'price'           => 65000.00,
            'discount_percent'=> null,
            'is_active'       => true,
            'manufacturer'    => 'Alcon',
            'article'         => 'DT1-0090',
            'model'           => 'Total 1',
            'coating'         => 'Water Gradient',
            'index'           => 1.42,
            'sph'             => -0.50,
            'cyl'             => null,
            'axis'            => null,
            'family'          => 'Dailies',
            'color'           => null,
            'option'          => 'Однодневные',
        ]);

        Product::query()->create([
            'name'            => 'Bausch + Lomb Ultra',
            'name_uz'         => 'Bausch + Lomb Ultra',
            'description'     => 'Ежемесячные линзы с MoistureSeal-технологией.',
            'description_uz'  => 'MoistureSeal texnologiyasi bilan oylik linzalar.',
            'slug'            => 'bausch-lomb-ultra',
            'category_id'     => $category->id,
            'price'           => 135000.00,
            'discount_percent'=> 15,
            'is_active'       => true,
            'manufacturer'    => 'Bausch & Lomb',
            'article'         => 'BLU-0030',
            'model'           => 'Ultra',
            'coating'         => 'MoistureSeal',
            'index'           => 1.47,
            'sph'             => -4.00,
            'cyl'             => null,
            'axis'            => null,
            'family'          => 'Ultra',
            'color'           => null,
            'option'          => 'Прозрачные',
        ]);

        Product::query()->create([
            'name'            => 'Eyeye Connect Dark Blue',
            'name_uz'         => 'Eyeye Connect Dark Blue',
            'description'     => 'Цветные ежемесячные линзы, тёмно-синий оттенок.',
            'description_uz'  => "Oylik rangli linzalar, to'q ko'k rang.",
            'slug'            => 'eyeye-connect-dark-blue',
            'category_id'     => $category->id,
            'price'           => 78000.00,
            'discount_percent'=> null,
            'is_active'       => true,
            'manufacturer'    => 'Eyeye',
            'article'         => 'EY-DB-01',
            'model'           => 'Connect',
            'coating'         => null,
            'index'           => 1.44,
            'sph'             => 0.00,
            'cyl'             => null,
            'axis'            => null,
            'family'          => 'Connect',
            'color'           => 'Dark Blue',
            'option'          => 'Цветные',
        ]);
    }
}
