<?php

namespace App\Filament\Resources\PrincipioAtivoResource\Pages;

use App\Filament\Resources\PrincipioAtivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePrincipioAtivos extends ManageRecords
{
    protected static string $resource = PrincipioAtivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
