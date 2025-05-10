<?php

namespace App\Filament\Resources\MoedaResource\Pages;

use App\Filament\Resources\MoedaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMoedas extends ManageRecords
{
    protected static string $resource = MoedaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
