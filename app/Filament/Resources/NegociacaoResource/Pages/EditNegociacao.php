<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Models\Moeda;
use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use Filament\Actions\DeleteAction;

class EditNegociacao extends EditRecord
{
    protected static string $resource = NegociacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $produtos = $this->record->negociacaoProdutos()->get();

        return array_merge($data, [
            'valor_total_pedido_rs' => $produtos->sum('total_preco_rs'),
            'valor_total_pedido_us' => $produtos->sum('total_preco_us'),
            'valor_total_pedido_rs_valorizado' => $produtos->sum('total_preco_valorizado_rs'),
            'valor_total_pedido_us_valorizado' => $produtos->sum('total_preco_valorizado_us'),
            'investimento_total_sacas' => $this->calculateInvestimento($data),
        ]);
    }

    #[On('negociacaoProdutoCreated')]
    #[On('negociacaoProdutoUpdated')]
    #[On('negociacaoProdutoDeleted')]
    public function refreshValores(): void
    {
        $state = $this->form->getState();
        $produtos = $this->record->negociacaoProdutos()->get();

        $updates = [
            'valor_total_pedido_rs' => $produtos->sum('total_preco_rs'),
            'valor_total_pedido_us' => $produtos->sum('total_preco_us'),
            'valor_total_pedido_rs_valorizado' => $produtos->sum('total_preco_valorizado_rs'),
            'valor_total_pedido_us_valorizado' => $produtos->sum('total_preco_valorizado_us'),
            'investimento_total_sacas' => $this->calculateInvestimento($state),
        ];

        $this->form->fill(array_merge($state, $updates));
    }

    protected function calculateInvestimento(array $state): float
    {
        $sigla = optional(Moeda::find($state['moeda_id'] ?? null))->sigla;
        $total = ($sigla === 'US$')
            ? ($state['valor_total_pedido_us'] ?? 0)
            : ($state['valor_total_pedido_rs'] ?? 0);
        $preco = $state['preco_liquido_saca'] ?: 1;

        return $preco > 0 ? ($total / $preco) : 0;
    }

    public function updatedPrecoLiquidoSaca($value): void
    {
        $state = $this->form->getState();
        $state['investimento_total_sacas'] = $this->calculateInvestimento($state);

        $this->form->fill($state);
    }

    public function updatedPrecoLiquidoSacaValorizado($value): void
    {
        $state = $this->form->getState();
        $state['preco_liquido_saca_valorizado'] = $value;
        $state['investimento_total_sacas'] = $this->calculateInvestimento($state);

        $this->form->fill($state);
    }
}
