<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            AdminUserSeeder::class,
            //UserFakeSeeder::class,
            ProdutosClassesSeeder::class,
            PrincipiosAtivosSeeder::class,
            MarcasComerciaisSeeder::class,
            UnidadesPesoSeeder::class,
            FamiliasSeeder::class,

                    // Novos seeders adicionados
        NivelValidacaoSeeder::class,
        StatusNegociacaoSeeder::class,
        CulturaSeeder::class,
        MoedaSeeder::class,
        PagamentoSeeder::class,
        ]);
    }
}
