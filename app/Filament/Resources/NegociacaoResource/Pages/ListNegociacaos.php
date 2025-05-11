<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNegociacaos extends ListRecords
{
    protected static string $resource = NegociacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
