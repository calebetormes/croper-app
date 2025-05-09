<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamiliasSeeder extends Seeder
{
    public function run()
    {
        $familias = ['BRONZE', 'PRATA', 'OURO', 'PRIME'];

        foreach ($familias as $nome) {
            DB::table('familias')->updateOrInsert(
                ['nome' => $nome]
            );
        }
    }
}
