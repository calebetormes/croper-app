<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadesPesoSeeder extends Seeder
{
    public function run()
    {
        $unidades = ['KG', 'L', 'GR', 'KG/L', 'DS'];

        foreach ($unidades as $sigla) {
            DB::table('unidades_peso')->updateOrInsert(
                ['sigla' => $sigla],
                ['descricao' => $sigla]
            );
        }
    }
}
