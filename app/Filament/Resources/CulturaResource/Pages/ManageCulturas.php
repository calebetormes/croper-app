<?php

namespace App\Filament\Resources\CulturaResource\Pages;

use App\Filament\Resources\CulturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCulturas extends ManageRecords
{
    protected static string $resource = CulturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
