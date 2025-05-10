<?php

namespace App\Filament\Resources\PracaCotacaoResource\Pages;

use App\Filament\Resources\PracaCotacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPracaCotacao extends EditRecord
{
    protected static string $resource = PracaCotacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
