<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Livewire\Attributes\On;



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

    // 1) Registra o listener
    protected function getListeners(): array
    {
        return array_merge(parent::getListeners(), [
            'negociacaoProdutoUpdated' => 'refreshValores',
        ]);
    }

    #[On('negociacaoProdutoUpdated')]
    public function refreshValores(): void
    {
        // 1) Recarrega somente o relacionamento/moeda
        $this->record->load('moeda');

        $isUsd = optional($this->record->moeda)->sigla === 'US$';

        // 2) Busca via relação (ignorando possível cast)
        $produtos = $this->record->negociacaoProdutos()->get();

        $totalSem = $produtos->sum(fn($item) => (
            $isUsd
            ? $item->snap_produto_preco_us
            : $item->snap_produto_preco_rs
        ) * $item->volume);

        $totalCom = $produtos->sum(fn($item) => (
            $isUsd
            ? $item->negociacao_produto_preco_virtual_us
            : $item->negociacao_produto_preco_virtual_rs
        ) * $item->volume);

        // 3) Merge do state existente + updates
        $state = $this->form->getState();
        $newState = array_merge($state, [
            'valor_total_sem_bonus' => $totalSem,
            'valor_total_com_bonus' => $totalCom,
        ]);

        $this->form->fill($newState);
    }

}