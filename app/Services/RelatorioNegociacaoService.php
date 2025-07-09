<?php

namespace App\Services;

use App\Models\Negociacao;

class RelatorioNegociacaoService
{
    /**
     * Busca a negociação com produtos carregados.
     */
    public function gerar(int $id): Negociacao
    {
        return Negociacao::with([
            'negociacaoProdutos.produto',
            'moeda',        // <— nova relação
            'gerente',      // <— nova relação
            'vendedor',     // <— nova relação
        ])
            ->findOrFail($id);
    }
}
