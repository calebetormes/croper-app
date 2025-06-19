<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Filament\Resources\PracaCotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\PracaCotacaoImporter;
use Filament\Actions\ImportAction;

class ListPracaCotacaos extends ListRecords
{
    protected static string $resource = PracaCotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Importar Praças')
                ->importer(PracaCotacaoImporter::class)
            //->delimiter(';'),       // <–– aqui
        ];
    }
}
