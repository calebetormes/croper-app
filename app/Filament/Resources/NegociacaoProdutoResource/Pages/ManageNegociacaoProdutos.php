<?php

namespace App\Filament\Resources\NegociacaoProdutoResource\Pages;

use App\Filament\Resources\NegociacaoProdutoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNegociacaoProdutos extends ManageRecords
{
    protected static string $resource = NegociacaoProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
