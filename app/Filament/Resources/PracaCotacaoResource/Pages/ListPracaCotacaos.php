<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Filament\Resources\PracaCotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\PracaCotacaoImporter;
use Filament\Actions\ImportAction;
use Filament\Notifications\Notification;
use Filament\Actions\Imports\Models\Import as FilamentImport;

class ListPracaCotacaos extends ListRecords
{
    protected static string $resource = PracaCotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Importar Praças')
                ->importer(PracaCotacaoImporter::class)
                // Processa inline: defina QUEUE_CONNECTION=sync no .env
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

            Actions\CreateAction::make()
                ->label('Nova Praça de Cotação'),
        ];
    }
}
