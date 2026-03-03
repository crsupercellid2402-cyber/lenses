<?php

namespace Database\Seeders;

use App\Models\PromotionSetting;
use Illuminate\Database\Seeder;

class PromotionSettingsSeeder extends Seeder
{
    public function run(): void
    {
        PromotionSetting::query()->create([
            'active_type'      => 'percent',
            'discount_percent' => 15,
        ]);

        PromotionSetting::query()->create([
            'active_type'      => 'none',
            'discount_percent' => null,
        ]);
    }
}
