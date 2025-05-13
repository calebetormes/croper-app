<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateNegociacao extends CreateRecord
{
    protected static string $resource = NegociacaoResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // 1) Extrai os itens do Repeater
        $itens = $data['negociacaoProdutos'] ?? [];
        unset($data['negociacaoProdutos']);

        // 2) Cria a negociação (campos da table negociacoes)
        $record = parent::handleRecordCreation($data);

        // 3) Para cada linha do repeater, cria o pivot via hasMany
        foreach ($itens as $item) {
            $record->negociacaoProdutos()->create($item);
        }

        return $record;
    }
}
