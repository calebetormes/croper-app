<?php

namespace App\Filament\Resources\NivelValidacaoResource\Pages;

use App\Filament\Resources\NivelValidacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNivelValidacaos extends ManageRecords
{
    protected static string $resource = NivelValidacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
