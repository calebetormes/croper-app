<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Filament\Resources\PracaCotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPracaCotacaos extends ListRecords
{
    protected static string $resource = PracaCotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
