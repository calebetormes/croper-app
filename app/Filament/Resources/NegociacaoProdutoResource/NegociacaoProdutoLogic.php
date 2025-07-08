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
        $set('snap_produto_preco_rs', number_format($produto->preco_rs, 2, '.', ''));
        $set('snap_produto_preco_us', number_format($produto->preco_us, 2, '.', ''));
        $set('snap_produto_custo_rs', number_format($produto->custo_rs, 2, '.', ''));
        $set('snap_produto_custo_us', number_format($produto->custo_us, 2, '.', ''));
        $set('indice_valorizacao', number_format($produto->indice_valorizacao_produto, 2, '.', ''));

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
        // Se não houver getters ou setters, sai
        if (!$get || !$set) {
            return;
        }

        //
        // 1) Obtenção dos valores base (preço e custo em R$ e US$) e do volume
        //
        $precoRs = $get('snap_produto_preco_rs') ?? 0;    // preço unitário em R$
        $custoRs = $get('snap_produto_custo_rs') ?? 0;    // custo unitário em R$
        $precoUs = $get('snap_produto_preco_us') ?? 0;    // preço unitário em US$
        $custoUs = $get('snap_produto_custo_us') ?? 0;    // custo unitário em US$
        $volume = $get('volume') ?? 0;    // quantidade negociada

        //
        // 2) Cálculo dos totais (preço * volume, custo * volume)
        //
        $totalPrecoRs = $precoRs * $volume;
        $totalCustoRs = $custoRs * $volume;
        $totalPrecoUs = $precoUs * $volume;
        $totalCustoUs = $custoUs * $volume;

        $set('preco_total_produto_negociacao_rs', $totalPrecoRs);
        $set('custo_total_produto_negociacao_rs', $totalCustoRs);
        $set('preco_total_produto_negociacao_us', $totalPrecoUs);
        $set('custo_total_produto_negociacao_us', $totalCustoUs);

        //
        // 3) Cálculo da margem absoluta (preço total - custo total)
        //
        $margemAbsRs = $totalPrecoRs - $totalCustoRs;
        $margemAbsUs = $totalPrecoUs - $totalCustoUs;

        $set('margem_faturamento_rs', $margemAbsRs);
        $set('margem_faturamento_us', $margemAbsUs);

        //
        // 4) Cálculo da margem percentual:
        //    fórmula: (1 - (custo unitário / preço unitário)) * 100
        //
        //    - Multiplicamos por 100 para converter em porcentagem
        //    - Arredondamos para 2 casas decimais
        //
        if ($precoRs > 0) {
            $margemPercRs = (1 - ($custoRs / $precoRs)) * 100;
            $margemPercRs = round($margemPercRs, 2);
        } else {
            // evita divisão por zero
            $margemPercRs = 0;
        }
        $set('margem_percentual_rs', $margemPercRs);

        if ($precoUs > 0) {
            $margemPercUs = (1 - ($custoUs / $precoUs)) * 100;
            $margemPercUs = round($margemPercUs, 2);
        } else {
            $margemPercUs = 0;
        }
        $set('margem_percentual_us', $margemPercUs);
    }

    public static function indiceValorizacaoAfterStateUpdated($get = null, $set = null)
    {
        if (!$get || !$set) {
            return;
        }

        $snapRs = floatval(str_replace(',', '.', $get('snap_produto_preco_rs') ?? '0'));
        $snapUs = floatval(str_replace(',', '.', $get('snap_produto_preco_us') ?? '0'));
        $indice = floatval(str_replace(',', '.', $get('indice_valorizacao') ?? '0'));

        $valorRs = number_format($snapRs * (1 + $indice), 2, '.', '');
        $valorUs = number_format($snapUs * (1 + $indice), 2, '.', '');

        $set('preco_produto_valorizado_rs', $valorRs);
        $set('preco_produto_valorizado_us', $valorUs);
    }

    public static function repeaterAfterStateUpdated($get = null, $set = null)
    {
        if (!$get || !$set) {
            return;
        }

        $items = $get('negociacaoProdutos') ?? [];

        // Receita e custos totais
        $totalRs = collect($items)
            ->sum(fn($i) => floatval(
                str_replace(',', '.', $i['snap_produto_preco_rs'] ?? 0)
            ) * floatval($i['volume'] ?? 0));

        $totalCustoRs = collect($items)
            ->sum(fn($i) => floatval(
                str_replace(',', '.', $i['snap_produto_custo_rs'] ?? 0)
            ) * floatval($i['volume'] ?? 0));

        $totalUs = collect($items)
            ->sum(fn($i) => floatval(
                str_replace(',', '.', $i['snap_produto_preco_us'] ?? 0)
            ) * floatval($i['volume'] ?? 0));

        $totalCustoUs = collect($items)
            ->sum(fn($i) => floatval(
                str_replace(',', '.', $i['snap_produto_custo_us'] ?? 0)
            ) * floatval($i['volume'] ?? 0));

        // margens absolutas
        $margemAbsRs = $totalRs - $totalCustoRs;
        $margemAbsUs = $totalUs - $totalCustoUs;

        // margens percentuais
        $margemPercRs = $totalRs > 0 ? (1 - ($totalCustoRs / $totalRs)) * 100 : 0;
        $margemPercUs = $totalUs > 0 ? (1 - ($totalCustoUs / $totalUs)) * 100 : 0;

        // arredonda
        $margemAbsRs = round($margemAbsRs, 2);
        $margemAbsUs = round($margemAbsUs, 2);
        $margemPercRs = round($margemPercRs, 2);
        $margemPercUs = round($margemPercUs, 2);

        // seta tudo no form state
        $set('margem_faturamento_total_rs', $margemAbsRs);
        $set('margem_faturamento_total_us', $margemAbsUs);
        $set('margem_percentual_total_rs', $margemPercRs);
        $set('margem_percentual_total_us', $margemPercUs);

        // Cálculo do índice de valorização médio
        $averageIndice = collect($items)
            ->map(fn($item) => floatval(str_replace(',', '.', ($item['indice_valorizacao'] ?? 0))))
            ->avg();

        $averageIndice = number_format($averageIndice, 2, '.', '');

        $set('indice_valorizacao_saca', $averageIndice);
        $set('valor_total_pedido_rs', $totalRs);
        $set('valor_total_pedido_us', $totalUs);
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
