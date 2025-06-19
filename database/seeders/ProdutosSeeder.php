<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produtos = [
            [
                'classe_id' => 1,
                'principio_ativo_id' => 1,
                'marca_comercial_id' => 1,
                'tipo_peso_id' => 1,
                'familia_id' => 1,
                'apresentacao' => 'Embalagem 10L',
                'dose_sugerida_hectare' => 2.50,
                'preco_rs' => 100.00,
                'preco_us' => 20.00,
                'custo_rs' => 80.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 2

            ],
            [
                'classe_id' => 1,
                'principio_ativo_id' => 2,
                'marca_comercial_id' => 2,
                'tipo_peso_id' => 1,
                'familia_id' => 2,
                'apresentacao' => 'Saco 1kg',
                'dose_sugerida_hectare' => 1.00,
                'preco_rs' => 120.00,
                'preco_us' => 24.00,
                'custo_rs' => 96.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 5
            ],
            [
                'classe_id' => 2,
                'principio_ativo_id' => 3,
                'marca_comercial_id' => 3,
                'tipo_peso_id' => 2,
                'familia_id' => 1,
                'apresentacao' => 'Frasco 500ml',
                'dose_sugerida_hectare' => 0.75,
                'preco_rs' => 80.00,
                'preco_us' => 16.00,
                'custo_rs' => 64.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 12
            ],
            [
                'classe_id' => 2,
                'principio_ativo_id' => 1,
                'marca_comercial_id' => 4,
                'tipo_peso_id' => 2,
                'familia_id' => 2,
                'apresentacao' => 'Pacote 200g',
                'dose_sugerida_hectare' => 0.50,
                'preco_rs' => 60.00,
                'preco_us' => 12.00,
                'custo_rs' => 48.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 1
            ],
            [
                'classe_id' => 3,
                'principio_ativo_id' => 2,
                'marca_comercial_id' => 1,
                'tipo_peso_id' => 1,
                'familia_id' => 3,
                'apresentacao' => 'Kit 2x5L',
                'dose_sugerida_hectare' => 3.00,
                'preco_rs' => 200.00,
                'preco_us' => 40.00,
                'custo_rs' => 160.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 2
            ],
            [
                'classe_id' => 1,
                'principio_ativo_id' => 4,
                'marca_comercial_id' => 2,
                'tipo_peso_id' => 3,
                'familia_id' => 1,
                'apresentacao' => 'Tabletes 100g',
                'dose_sugerida_hectare' => 0.25,
                'preco_rs' => 50.00,
                'preco_us' => 10.00,
                'custo_rs' => 40.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 2
            ],
            [
                'classe_id' => 3,
                'principio_ativo_id' => 3,
                'marca_comercial_id' => 3,
                'tipo_peso_id' => 3,
                'familia_id' => 3,
                'apresentacao' => 'Embalagem 20kg',
                'dose_sugerida_hectare' => 5.00,
                'preco_rs' => 300.00,
                'preco_us' => 60.00,
                'custo_rs' => 240.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 3
            ],
            [
                'classe_id' => 2,
                'principio_ativo_id' => 4,
                'marca_comercial_id' => 4,
                'tipo_peso_id' => 2,
                'familia_id' => 2,
                'apresentacao' => 'Frasco 1L',
                'dose_sugerida_hectare' => 1.20,
                'preco_rs' => 90.00,
                'preco_us' => 18.00,
                'custo_rs' => 72.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 4
            ],
            [
                'classe_id' => 3,
                'principio_ativo_id' => 1,
                'marca_comercial_id' => 1,
                'tipo_peso_id' => 1,
                'familia_id' => 3,
                'apresentacao' => 'Saco 10kg',
                'dose_sugerida_hectare' => 4.00,
                'preco_rs' => 250.00,
                'preco_us' => 50.00,
                'custo_rs' => 200.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 20
            ],
            [
                'classe_id' => 1,
                'principio_ativo_id' => 2,
                'marca_comercial_id' => 2,
                'tipo_peso_id' => 3,
                'familia_id' => 1,
                'apresentacao' => 'Ampola 250ml',
                'dose_sugerida_hectare' => 0.90,
                'preco_rs' => 70.00,
                'preco_us' => 14.00,
                'custo_rs' => 56.00,
                'custo_us' => 16.00,
                'indice_valorizacao_produto' => 3
            ],
        ];

        foreach ($produtos as $data) {
            Produto::create($data);
        }
    }
}
