<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()->create([
            'login' => 'test@gmail.com',
            'password' => Hash::make('test@gmail.com'),
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            BotUsersSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
            ImagesSeeder::class,
            StocksSeeder::class,
            AttributesSeeder::class,
            LandingCarouselsSeeder::class,
            PromotionSettingsSeeder::class,
            TransactionsSeeder::class,
            CartsSeeder::class,
            OrdersSeeder::class,
            StockHistoriesSeeder::class,
            FavoritesSeeder::class,
            ReviewsSeeder::class,
            SupportSeeder::class,
        ]);
    }
}
