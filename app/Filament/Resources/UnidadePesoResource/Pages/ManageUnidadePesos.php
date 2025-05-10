<?php

namespace App\Filament\Resources\UnidadePesoResource\Pages;

use App\Filament\Resources\UnidadePesoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUnidadePesos extends ManageRecords
{
    protected static string $resource = UnidadePesoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
