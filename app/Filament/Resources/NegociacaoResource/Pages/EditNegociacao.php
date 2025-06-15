<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditNegociacao extends EditRecord
{
    protected static string $resource = NegociacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
