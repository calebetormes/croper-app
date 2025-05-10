<?php

namespace App\Filament\Resources\MarcaComercialResource\Pages;

use App\Filament\Resources\MarcaComercialResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMarcaComercials extends ManageRecords
{
    protected static string $resource = MarcaComercialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
