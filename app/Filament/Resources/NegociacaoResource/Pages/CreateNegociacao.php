<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateNegociacao extends CreateRecord
{
    protected static string $resource = NegociacaoResource::class;

    protected function getRedirectUrl(): string
    {
        // após criar, redireciona para a rota de edit
        // e já abre a aba "produtos" (nome definido no Tabs)
        return static::getResource()::getUrl(
            'edit',
            ['record' => $this->record],
        )
            // opcional: você pode anexar um fragmento de hash se quiser
            // para scroll ou JS customizado: ->withFragment('produtos');
        ;
    }
}
