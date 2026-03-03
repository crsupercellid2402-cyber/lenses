<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    public function run(): void
    {
        $nowMs = (string) (now()->timestamp * 1000);

        Transaction::query()->create([
            'paycom_transaction_id' => 'paycoms_txn_001',
            'paycom_time'           => $nowMs,
            'paycom_time_datetime'  => now()->toDateTimeString(),
            'create_time'           => now(),
            'perform_time'          => now(),
            'cancel_time'           => null,
            'amount'                => 89000,
            'state'                 => 2,
            'reason'                => null,
            'receivers'             => null,
            'order_id'              => '1',
            'perform_time_unix'     => $nowMs,
        ]);

        Transaction::query()->create([
            'paycom_transaction_id' => 'paycoms_txn_002',
            'paycom_time'           => $nowMs,
            'paycom_time_datetime'  => now()->toDateTimeString(),
            'create_time'           => now(),
            'perform_time'          => null,
            'cancel_time'           => $nowMs,
            'amount'                => 120000,
            'state'                 => -1,
            'reason'                => 3,
            'receivers'             => null,
            'order_id'              => '2',
            'perform_time_unix'     => null,
        ]);
    }
}
