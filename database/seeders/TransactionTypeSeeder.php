<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->insert([
                [
                    'Description' => 'Depósito'
                ],
                [
                    'Description' => 'Saque'
                ],
                [
                    'Description' => 'Compra'
                ],
                [
                    'Description' => 'Venda'
                ]
            ]);
    }
}
