<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Models\Cultura;
use App\Models\Moeda;
use App\Models\PracaCotacao;
use Carbon\Carbon;
use League\Csv\Reader;

class ImportPracaCotacao
{
    public static function import(string $relativePath): void
    {
        $path = storage_path('app/public/' . $relativePath);

        if (!file_exists($path)) {
            throw new \Exception("Arquivo CSV não encontrado: $path");
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        foreach ($csv->getRecords() as $row) {
            $cidade = trim($row['PRAÇA'] ?? '');
            $precoTxt = trim($row['PREÇO'] ?? '');
            $vencimento = trim($row['VENCIMENTO'] ?? '');
            $moedaNome = trim($row['MOEDA'] ?? '');
            $culturaNome = trim($row['CULTURA'] ?? '');
            $fatorTxt = trim($row['FATOR_VALORIZACAO'] ?? '');

            if (!$cidade || !$precoTxt || !$vencimento || !$moedaNome || !$culturaNome || !$fatorTxt) {
                continue;
            }

            $moeda = Moeda::where('nome', $moedaNome)
                ->orWhere('sigla', $moedaNome)
                ->first();

            $cultura = Cultura::where('nome', $culturaNome)->first();

            if (!$moeda || !$cultura) {
                continue;
            }

            // Conversão do preço (existente)
            $valor = preg_replace('/[^0-9,]/', '', $precoTxt);
            $valor = str_replace(',', '.', $valor);

            // Conversão do fator de valorização
            $fator = preg_replace('/[^0-9,]/', '', $fatorTxt);
            $fator = str_replace(',', '.', $fator);

            try {
                $dataVenc = Carbon::createFromFormat('d/m/Y', $vencimento)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }

            PracaCotacao::create([
                'cidade' => $cidade,
                'data_vencimento' => $dataVenc,
                'praca_cotacao_preco' => floatval($valor),
                'moeda_id' => $moeda->id,
                'cultura_id' => $cultura->id,
                'fator_valorizacao' => floatval($fator),
            ]);
        }
    }
}
