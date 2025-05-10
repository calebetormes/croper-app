<?php

namespace App\Filament\Resources\FamiliaResource\Pages;

use App\Filament\Resources\FamiliaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFamilias extends ManageRecords
{
    protected static string $resource = FamiliaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
