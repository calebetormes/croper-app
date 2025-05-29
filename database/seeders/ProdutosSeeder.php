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
                'preco_real_rs' => 100.00,
                'preco_real_us' => 20.00,
                'preco_virtual_rs' => 90.00,
                'preco_virtual_us' => 18.00,
                'custo_rs' => 80.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 2

            ],
            [
                'classe_id' => 1,
                'principio_ativo_id' => 2,
                'marca_comercial_id' => 2,
                'tipo_peso_id' => 1,
                'familia_id' => 2,
                'apresentacao' => 'Saco 1kg',
                'dose_sugerida_hectare' => 1.00,
                'preco_real_rs' => 120.00,
                'preco_real_us' => 24.00,
                'preco_virtual_rs' => 110.00,
                'preco_virtual_us' => 22.00,
                'custo_rs' => 96.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 5
            ],
            [
                'classe_id' => 2,
                'principio_ativo_id' => 3,
                'marca_comercial_id' => 3,
                'tipo_peso_id' => 2,
                'familia_id' => 1,
                'apresentacao' => 'Frasco 500ml',
                'dose_sugerida_hectare' => 0.75,
                'preco_real_rs' => 80.00,
                'preco_real_us' => 16.00,
                'preco_virtual_rs' => 72.00,
                'preco_virtual_us' => 14.40,
                'custo_rs' => 64.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 12
            ],
            [
                'classe_id' => 2,
                'principio_ativo_id' => 1,
                'marca_comercial_id' => 4,
                'tipo_peso_id' => 2,
                'familia_id' => 2,
                'apresentacao' => 'Pacote 200g',
                'dose_sugerida_hectare' => 0.50,
                'preco_real_rs' => 60.00,
                'preco_real_us' => 12.00,
                'preco_virtual_rs' => 54.00,
                'preco_virtual_us' => 10.80,
                'custo_rs' => 48.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 1
            ],
            [
                'classe_id' => 3,
                'principio_ativo_id' => 2,
                'marca_comercial_id' => 1,
                'tipo_peso_id' => 1,
                'familia_id' => 3,
                'apresentacao' => 'Kit 2x5L',
                'dose_sugerida_hectare' => 3.00,
                'preco_real_rs' => 200.00,
                'preco_real_us' => 40.00,
                'preco_virtual_rs' => 180.00,
                'preco_virtual_us' => 36.00,
                'custo_rs' => 160.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 2
            ],
            [
                'classe_id' => 1,
                'principio_ativo_id' => 4,
                'marca_comercial_id' => 2,
                'tipo_peso_id' => 3,
                'familia_id' => 1,
                'apresentacao' => 'Tabletes 100g',
                'dose_sugerida_hectare' => 0.25,
                'preco_real_rs' => 50.00,
                'preco_real_us' => 10.00,
                'preco_virtual_rs' => 45.00,
                'preco_virtual_us' => 9.00,
                'custo_rs' => 40.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 2
            ],
            [
                'classe_id' => 3,
                'principio_ativo_id' => 3,
                'marca_comercial_id' => 3,
                'tipo_peso_id' => 3,
                'familia_id' => 3,
                'apresentacao' => 'Embalagem 20kg',
                'dose_sugerida_hectare' => 5.00,
                'preco_real_rs' => 300.00,
                'preco_real_us' => 60.00,
                'preco_virtual_rs' => 270.00,
                'preco_virtual_us' => 54.00,
                'custo_rs' => 240.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 3
            ],
            [
                'classe_id' => 2,
                'principio_ativo_id' => 4,
                'marca_comercial_id' => 4,
                'tipo_peso_id' => 2,
                'familia_id' => 2,
                'apresentacao' => 'Frasco 1L',
                'dose_sugerida_hectare' => 1.20,
                'preco_real_rs' => 90.00,
                'preco_real_us' => 18.00,
                'preco_virtual_rs' => 81.00,
                'preco_virtual_us' => 16.20,
                'custo_rs' => 72.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 4
            ],
            [
                'classe_id' => 3,
                'principio_ativo_id' => 1,
                'marca_comercial_id' => 1,
                'tipo_peso_id' => 1,
                'familia_id' => 3,
                'apresentacao' => 'Saco 10kg',
                'dose_sugerida_hectare' => 4.00,
                'preco_real_rs' => 250.00,
                'preco_real_us' => 50.00,
                'preco_virtual_rs' => 225.00,
                'preco_virtual_us' => 45.00,
                'custo_rs' => 200.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 20
            ],
            [
                'classe_id' => 1,
                'principio_ativo_id' => 2,
                'marca_comercial_id' => 2,
                'tipo_peso_id' => 3,
                'familia_id' => 1,
                'apresentacao' => 'Ampola 250ml',
                'dose_sugerida_hectare' => 0.90,
                'preco_real_rs' => 70.00,
                'preco_real_us' => 14.00,
                'preco_virtual_rs' => 63.00,
                'preco_virtual_us' => 12.60,
                'custo_rs' => 56.00,
                'custo_us' => 16.00,
                'fator_multiplicador' => 3
            ],
        ];

        foreach ($produtos as $data) {
            Produto::create($data);
        }
    }
}
