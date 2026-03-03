<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Database\Seeder;

class StockHistoriesSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = Stock::query()->take(2)->get();
        $admin  = Admin::query()->first();

        if ($stocks->isEmpty()) {
            return;
        }

        foreach ($stocks as $stock) {
            StockHistory::query()->create([
                'stock_id'          => $stock->id,
                'type'              => 'plus',
                'source'            => 'manual',
                'quantity'          => $stock->quantity,
                'previous_quantity' => 0,
                'difference'        => $stock->quantity,
                'updated_by'        => $admin?->id,
                'user_id'           => null,
                'order_id'          => null,
            ]);

            StockHistory::query()->create([
                'stock_id'          => $stock->id,
                'type'              => 'minus',
                'source'            => 'manual',
                'quantity'          => $stock->quantity - 5,
                'previous_quantity' => $stock->quantity,
                'difference'        => -5,
                'updated_by'        => $admin?->id,
                'user_id'           => null,
                'order_id'          => null,
            ]);
        }
    }
}
