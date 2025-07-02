<?php

namespace App\Filament\Resources\NegociacaoProdutoResource\Forms;

use App\Models\Produto;
use App\Models\Moeda;

class NegociacaoProdutoLogic
{
    public static function produtoSelectAfterStateUpdated($get = null, $set = null)
    {
        if (!$get || !$set) {
            return;
        }

        $produto = Produto::find($get('produto_id'));
        if (!$produto) {
            return;
        }

        // snapshot
        $set('snap_produto_preco_rs', $produto->preco_rs);
        $set('snap_produto_preco_us', $produto->preco_us);
        $set('snap_produto_custo_rs', $produto->custo_rs);
        $set('snap_produto_custo_us', $produto->custo_us);
        $set('indice_valorizacao', $produto->indice_valorizacao_produto);

        if (!$get('data_atualizacao_snap_precos_produtos')) {
            $set('data_atualizacao_snap_precos_produtos', now());
        }

        // recalc all
        self::volumeAfterStateUpdated($get, $set);
        self::indiceValorizacaoAfterStateUpdated($get, $set);
        self::repeaterAfterStateUpdated($get, $set);
    }

    public static function volumeAfterStateUpdated($get = null, $set = null)
    {
        if (!$get || !$set) {
            return;
        }

        $precoRs = $get('snap_produto_preco_rs') ?? 0;
        $precoUs = $get('snap_produto_preco_us') ?? 0;
        $custoRs = $get('snap_produto_custo_rs') ?? 0;
        $custoUs = $get('snap_produto_custo_us') ?? 0;
        $volume = $get('volume') ?? 0;

        $set('preco_total_produto_negociacao_rs', $precoRs * $volume);
        $set('preco_total_produto_negociacao_us', $precoUs * $volume);
        $set('custo_total_produto_negociacao_rs', $custoRs * $volume);
        $set('custo_total_produto_negociacao_us', $custoUs * $volume);

        $set('margem_faturamento_rs', $precoRs * $volume - $custoRs * $volume);
        $set('margem_faturamento_us', $precoUs * $volume - $custoUs * $volume);
    }

    public static function indiceValorizacaoAfterStateUpdated($get = null, $set = null)
    {
        if (!$get || !$set) {
            return;
        }

        $snapRs = floatval(str_replace(',', '.', $get('snap_produto_preco_rs') ?? '0'));
        $snapUs = floatval(str_replace(',', '.', $get('snap_produto_preco_us') ?? '0'));
        $indice = floatval(str_replace(',', '.', $get('indice_valorizacao') ?? '0'));

        $set('preco_produto_valorizado_rs', $snapRs * (1 + $indice));
        $set('preco_produto_valorizado_us', $snapUs * (1 + $indice));
    }

    public static function repeaterAfterStateUpdated($get = null, $set = null)
    {
        if (!$get || !$set) {
            return;
        }

        $items = $get('negociacaoProdutos') ?? [];
        $totalRs = collect($items)
            ->sum(
                fn($item) =>
                floatval(str_replace(',', '.', ($item['snap_produto_preco_rs'] ?? 0))) *
                floatval($item['volume'] ?? 0)
            );
        $totalUs = collect($items)
            ->sum(
                fn($item) =>
                floatval(str_replace(',', '.', ($item['snap_produto_preco_us'] ?? 0))) *
                floatval($item['volume'] ?? 0)
            );

        $set('valor_total_pedido_rs', $totalRs);
        $set('valor_total_pedido_us', $totalUs);

        $averageIndice = collect($items)
            ->map(fn($item) => floatval(str_replace(',', '.', ($item['indice_valorizacao'] ?? 0))))
            ->avg();

        $set('indice_valorizacao_saca', $averageIndice);
        $set('valor_total_pedido_rs_valorizado', $totalRs * (1 + $averageIndice));
        $set('valor_total_pedido_us_valorizado', $totalUs * (1 + $averageIndice));

        $rawPrecoSaca = $get('preco_liquido_saca') ?? '0';
        $rawArea = $get('area_hectares') ?? '1';
        $rawMoedaId = $get('moeda_id') ?? null;
        $sigla = optional(Moeda::find($rawMoedaId))->sigla;
        $precoSaca = floatval(str_replace(',', '.', $rawPrecoSaca)) ?: 1;
        $areaHectare = floatval(str_replace(',', '.', $rawArea)) ?: 1;

        $set('preco_liquido_saca_valorizado', round($precoSaca * (1 + $averageIndice), 2));

        $base = strtoupper($sigla) === 'USD' ? $totalUs : $totalRs;
        $investSacas = $base / $precoSaca;
        $set('investimento_total_sacas', round($investSacas, 2));
        $set('investimento_sacas_hectare', round($investSacas / $areaHectare, 2));

        $set('peso_total_kg', round($investSacas * 60, 2));

        $bonus = strtoupper($sigla) === 'USD'
            ? ($totalUs * (1 + $averageIndice) - $totalUs)
            : ($totalRs * (1 + $averageIndice) - $totalRs);

        $set('bonus_cliente_pacote', round($bonus, 2));
    }
}
