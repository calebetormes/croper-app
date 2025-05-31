<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PracaCotacao;
use App\Models\Moeda;
use App\Models\Cultura;

class PracaCotacaoSeeder extends Seeder
{
    public function run(): void
    {
        $moeda = Moeda::where('nome', 'LIKE', 'Real%')->first();
        $cultura = Cultura::where('nome', 'Soja')->first();

        if (!$moeda || !$cultura) {
            $this->command->error('Moeda ou Cultura não encontradas. Verifique se existem nas tabelas.');
            return;
        }

        $cotacoes = [
            ['cidade' => 'SELECIONE A PRAÇA DESEJADA', 'valor' => 0.00],
            ['cidade' => 'AGUA BOA-MT', 'valor' => 101.54],
            ['cidade' => 'RONDONOPOLIS-MT', 'valor' => 106.38],
            ['cidade' => 'CAMPO NOVO PARECIS-MT', 'valor' => 99.61],
            ['cidade' => 'SORRISO-MT', 'valor' => 98.64],
            ['cidade' => 'JUARA-MT', 'valor' => 97.42],
            ['cidade' => 'BRASNORTE-MT', 'valor' => 97.52],
            ['cidade' => 'SINOP-MT', 'valor' => 97.62],
            ['cidade' => 'QUERENCIA-MT', 'valor' => 97.84],
            ['cidade' => 'PRIMAVERA DO LESTE-MT', 'valor' => 103.67],
        ];

        foreach ($cotacoes as $cotacao) {
            PracaCotacao::create([
                'cidade' => $cotacao['cidade'],
                'data_vencimento' => '2025-05-30',
                'praca_cotacao_preco' => $cotacao['valor'],
                'moeda_id' => $moeda->id,
                'cultura_id' => $cultura->id,
            ]);
        }

        $this->command->info('Praças de cotação inseridas com sucesso!');
    }
}
