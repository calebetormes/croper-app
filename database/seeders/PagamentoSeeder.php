<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagamentoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pagamentos')->insert(['data_pagamento' => '2025-01-10', 'data_entrega' => '2025-01-15']);
        DB::table('pagamentos')->insert(['data_pagamento' => '2025-02-05', 'data_entrega' => '2025-02-10']);
    }
}
