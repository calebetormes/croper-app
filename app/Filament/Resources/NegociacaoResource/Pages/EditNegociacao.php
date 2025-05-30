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

    protected function getSaveFormAction(): Action
    {
        $action = Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            // forma 1: closure sem return
            ->action(function (): void {
                $this->save();
            })
            ->keyBindings(['mod+s']);

        if ($this->record->data_atualizacao_snap_preco_praca_cotacao) {
            $days = Carbon::parse($this->record->data_atualizacao_snap_preco_praca_cotacao)
                ->diffInDays(now());
            $days = (int) round(
                Carbon::parse($this->record->data_atualizacao_snap_preco_praca_cotacao)
                    ->diffInDays(now())
            );
            if ($days >= 3) {
                $action
                    ->requiresConfirmation()
                    ->modalHeading('Atenção: preços desatualizados')
                    ->modalDescription("Já se passaram {$days} dias desde a última atualização de preços da praça. Deseja continuar mesmo assim?");
            }
        }

        return $action;
    }

}