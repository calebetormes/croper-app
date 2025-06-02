<?php
namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Models\PracaCotacao;
use League\Csv\Writer;
use SplTempFileObject;
use Carbon\Carbon;

class ExportPracaCotacao
{
    public static function export()
    {
        // Cria um arquivo CSV em memória
        $temp = new SplTempFileObject();
        $csv = Writer::createFromFileObject($temp);
        $csv->setDelimiter(';');

        // Cabeçalho
        $csv->insertOne([
            'PRAÇA',
            'PREÇO',
            'VENCIMENTO',
            'MOEDA',
            'CULTURA',
            'FATOR_VALORIZACAO',
        ]);

        // Registros
        $registros = PracaCotacao::with(['moeda', 'cultura'])->get();
        foreach ($registros as $item) {
            $csv->insertOne([
                $item->cidade,
                number_format($item->praca_cotacao_preco, 2, ',', '.'),
                Carbon::createFromFormat('Y-m-d', $item->data_vencimento)
                    ->format('d/m/Y'),
                $item->moeda->nome,
                $item->cultura->nome,
                number_format($item->fator_valorizacao, 2, ',', '.'),
            ]);
        }

        $temp->rewind();

        return response()->streamDownload(
            function () use ($temp) {
                $temp->rewind();
                while (!$temp->eof()) {
                    echo $temp->fgets();
                }
            },
            'pracas_cotacoes_export.csv',
            ['Content-Type' => 'text/csv']
        );
    }
}
