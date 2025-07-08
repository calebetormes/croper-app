<?php
// app/Filament/Resources/NegociacaoResource/Infolist/Sections/StatusInfolist.php

namespace App\Filament\Resources\NegociacaoResource\Infolist\Sections;

use App\Filament\Resources\NegociacaoResource\Forms\Sections\StatusGeralSectionForm;
use App\Models\Negociacao;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Enums\ActionSize;

class StatusInfolist
{
    public static function make(): InfolistSection
    {
        return InfolistSection::make('Status')
            ->columns(4)
            ->schema([
                TextEntry::make('statusNegociacao.nome')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Em análise' => 'warning',
                        'Aprovado' => 'success',
                        'Não Aprovado' => 'danger',
                        'Concluído' => 'secondary',
                        default => 'secondary',
                    }),
                TextEntry::make('nivelValidacao.nome')
                    ->label('Nível de Validação')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'primary',
                        '2' => 'warning',
                        '3' => 'danger',
                        default => 'secondary',
                    }),
                TextEntry::make('observacoes')
                    ->label('Observações'),
                Actions::make([
                    Action::make('changeStatus')
                        ->label('Alterar Status')
                        ->icon('heroicon-o-adjustments-vertical')
                        ->size(ActionSize::Large)
                        ->form([StatusGeralSectionForm::make()])
                        ->modalHeading('Alterar Status da Negociação')
                        ->modalWidth('xl')
                        ->requiresConfirmation()
                        //->action(fn(array $data, Negociacao $record): void => $record->fill($data)->save())
                        ->button(),
                ]),
            ]);
    }
}
