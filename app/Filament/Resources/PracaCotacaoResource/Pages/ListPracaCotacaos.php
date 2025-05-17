<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Filament\Resources\PracaCotacaoResource;
use App\Models\Cultura;
use App\Models\Moeda;
use App\Models\PracaCotacao;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use League\Csv\Reader;

class ListPracaCotacaos extends ListRecords
{
    protected static string $resource = PracaCotacaoResource::class;

    public function getHeaderActions(): array
    {
        return [
            Action::make('importarCsv')
                ->label('Importar CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('arquivo')
                        ->label('Arquivo CSV')
                        ->acceptedFileTypes(['text/csv'])
                        ->disk('public')              // ✅ usa disco public
                        ->directory('csv')            // ✅ salva em storage/app/public/csv
                        ->preserveFilenames()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $relativePath = $data['arquivo']; // ex: csv/arquivo.csv
                    $path = storage_path('app/public/'.$relativePath); // caminho real

                    if (! file_exists($path)) {
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

                        if (! $cidade || ! $precoTxt || ! $vencimento || ! $moedaNome || ! $culturaNome) {
                            continue;
                        }

                        $moeda = Moeda::where('nome', $moedaNome)
                            ->orWhere('sigla', $moedaNome)
                            ->first();

                        $cultura = Cultura::where('nome', $culturaNome)->first();

                        if (! $moeda || ! $cultura) {
                            continue;
                        }

                        $valor = preg_replace('/[^0-9,]/', '', $precoTxt);
                        $valor = str_replace(',', '.', $valor);

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
                        ]);
                    }
                })
                ->successNotificationTitle('Importação concluída com sucesso!'),
        ];
    }
}
