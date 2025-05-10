<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CulturaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('culturas')->insert(['nome' => 'Soja', 'descricao' => 'Cultura de Soja']);
        DB::table('culturas')->insert(['nome' => 'Milho', 'descricao' => 'Cultura de Milho']);
        DB::table('culturas')->insert(['nome' => 'Algodão', 'descricao' => 'Cultura de Algodão']);
    }
}
