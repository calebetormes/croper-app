<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Filament\Exports\PracaCotacaoExporter;
use App\Filament\Imports\PracaCotacaoImporter;
use App\Filament\Resources\PracaCotacaoResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportAction as PageExportAction;
use Filament\Actions\Imports\Models\Import as FilamentImport;

class ListPracaCotacaos extends ListRecords
{
    protected static string $resource = PracaCotacaoResource::class;

    /**
     * Actions no cabeçalho da página: Importar, Exportar e Criar
     */
    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Importar Praças')
                ->importer(PracaCotacaoImporter::class)
                ->after(function (FilamentImport $import) {
                    $failedCount = $import->getFailedRowsCount();

                    if ($failedCount > 0) {
                        Notification::make()
                            ->danger()
                            ->title("{$failedCount} linhas falharam na importação.")
                            ->body('Confira o CSV de falhas no sininho de notificações.')
                            ->send();
                    } else {
                        $successCount = $import->successful_rows;

                        Notification::make()
                            ->success()
                            ->title('Importação concluída')
                            ->body("Importadas {$successCount} praças com sucesso!")
                            ->send();
                    }
                }),

            PageExportAction::make()
                ->label('Exportar CSV')
                ->exporter(PracaCotacaoExporter::class)
                ->fileName('pracas_cotacao_export.csv'),

            Actions\CreateAction::make()
                ->label('Nova Praça de Cotação'),
        ];
    }
}
