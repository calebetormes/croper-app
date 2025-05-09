<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutosClassesSeeder extends Seeder
{
    public function run()
    {
        $classes = ['H', 'I', 'F', 'BIO', 'ESP', 'OL', 'POL'];

        foreach ($classes as $nome) {
            DB::table('produtos_classes')->updateOrInsert(
                ['nome' => $nome]
            );
        }
    }
}
