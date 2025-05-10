<?php

namespace App\Filament\Resources\StatusNegociacaoResource\Pages;

use App\Filament\Resources\StatusNegociacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusNegociacaos extends ManageRecords
{
    protected static string $resource = StatusNegociacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
