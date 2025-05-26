<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ProdutoResource\Pages\ProdutoImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ManageProdutos extends ManageRecords
{
    protected static string $resource = ProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
            Actions\Action::make('importarCSV')
                ->label('Importar CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('Arquivo CSV')
                        ->acceptedFileTypes(['text/csv', '.csv'])
                        ->disk('local') // armazena em storage/app
                        ->required(),
                ])
                ->action(function (array $data): void {
                    // monta o caminho completo do CSV no disco local
                    $relativePath = $data['csv_file'];
                    $fullPath = storage_path('app/' . $relativePath);

                    // importa em massa
                    Excel::import(new ProdutoImport(), $fullPath);

                    Notification::make()
                        ->success()
                        ->title('Importação concluída!')
                        ->body('Todos os produtos foram importados com sucesso.')
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
