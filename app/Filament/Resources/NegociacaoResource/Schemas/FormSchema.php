<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use App\Filament\Resources\NegociacaoResource\Forms\Sections\BasicInformationSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ClientInformationSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\CotacoesSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\PagamentosSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\StatusGeralSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ValoresSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\NegociacaoProdutoSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\QuantidadeMinimaSectionForm;
use Filament\Forms\Components\Tabs;



class FormSchema
{
    public static function make(): array
    {
        return [
            Tabs::make('Negociação')
                ->tabs([
                    Tabs\Tab::make('Informações Básicas')
                        ->schema([
                            BasicInformationSectionForm::make(),
                            ClientInformationSectionForm::make(),
                            PagamentosSectionForm::make(),
                            CotacoesSectionForm::make(),
                        ]),

                    Tabs\Tab::make('Produtos')
                        ->schema([
                            NegociacaoProdutoSectionForm::make(),
                            QuantidadeMinimaSectionForm::make(),
                        ]),

                    Tabs\Tab::make('Valores e Status')
                        ->schema([
                            ValoresSectionForm::make(),
                            //StatusValidacoesSectionForm::make(),
                            //StatusGeralSectionForm::make(),
                        ]),


                ])
                ->columnSpanFull(),
        ];
    }
}
