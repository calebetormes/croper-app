<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;
use Filament\Actions\ImportAction;
use App\Filament\Imports\ProdutoImporter;
use Filament\Actions\ExportAction as PageExportAction;
use App\Filament\Exports\ProdutoExporter;


class ManageProdutos extends ManageRecords
{
    protected static string $resource = ProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Importar Produtos')
                ->icon('heroicon-o-arrow-up-tray')
                ->importer(ProdutoImporter::class)           // 👈 aqui
                ->after(function (\Filament\Actions\Imports\Models\Import $import) {
                    $failed = $import->getFailedRowsCount();
                    if ($failed > 0) {
                        Notification::make()
                            ->danger()
                            ->title("{$failed} linhas falharam.")
                            ->body('Confira o CSV de falhas no sino de notificações.')
                            ->send();
                    } else {
                        Notification::make()
                            ->success()
                            ->title('Importação concluída')
                            ->body("Foram importados {$import->successful_rows} produtos com sucesso!")
                            ->send();
                    }
                }),

            // Botão de export
            PageExportAction::make()
                ->label('Exportar CSV')
                ->exporter(ProdutoExporter::class)
                ->fileName('produtos_export.csv'),

            Actions\CreateAction::make(),
        ];
    }
}
