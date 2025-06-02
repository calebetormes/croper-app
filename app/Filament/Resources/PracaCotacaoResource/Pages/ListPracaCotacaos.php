<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Filament\Resources\PracaCotacaoResource;
use App\Filament\Resources\PracaCotacaoResource\Pages\ImportPracaCotacao;
use App\Models\PracaCotacao;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;

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
                        ->disk('public')      // usa disco public
                        ->directory('csv')    // salva em storage/app/public/csv
                        ->preserveFilenames()
                        ->required(),
                ])
                ->action(function (array $data) {
                    ImportPracaCotacao::import($data['arquivo']);
                })
                ->successNotificationTitle('Importação concluída com sucesso!'),

            Action::make('exportarCsv')    // ← nova action
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return ExportPracaCotacao::export();
                })
                ->color('primary'),

            Actions\CreateAction::make()
                ->label('Adicionar Nova Praça de Cotação')
                ->url(fn() => PracaCotacaoResource::getUrl('create')),
        ];
    }
}
