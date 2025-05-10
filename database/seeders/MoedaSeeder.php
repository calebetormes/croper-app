<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoedaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('moedas')->insert(['nome' => 'Real', 'sigla' => 'BRL']);
        DB::table('moedas')->insert(['nome' => 'Dólar', 'sigla' => 'USD']);
    }
}
