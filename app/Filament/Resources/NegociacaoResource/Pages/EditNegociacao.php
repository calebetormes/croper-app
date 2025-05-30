<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Filament\Actions\FormAction;
use Filament\Actions\Action;
use Carbon\Carbon;



class EditNegociacao extends EditRecord
{
    protected static string $resource = NegociacaoResource::class;

    // cabeçalho: botão de excluir
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}