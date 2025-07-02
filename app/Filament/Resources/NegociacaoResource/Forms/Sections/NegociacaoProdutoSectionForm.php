<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\NegociacaoProdutoForm;

class NegociacaoProdutoSectionForm
{
    public static function make(): Section
    {
        return Section::make('Produtos')
            ->schema(
                NegociacaoProdutoForm::make()
            );
    }
}
