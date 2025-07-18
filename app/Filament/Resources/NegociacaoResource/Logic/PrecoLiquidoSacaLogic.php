<?php

namespace App\Filament\Resources\NegociacaoResource\Logic;

use Filament\Forms\Get;
use Filament\Forms\Set;

class PrecoLiquidoSacaLogic
{
    /**
     * Calcula e atualiza o preço líquido da saca com base no preço da praça e no índice de valorização.
     *
     * @param Get $get  Closure para obter valores de outros campos do form
     * @param Set $set  Closure para atribuir valores a campos do form
     */
    public static function updatePrecoLiquidoSaca(Get $get, Set $set): void
    {
        // Obtém valores brutos (string ou número)
        $rawPrecoPraca = $get('snap_praca_cotacao_preco') ?? 0;
        $rawIndice = $get('indice_valorizacao_saca') ?? 0;

        // Converte para float, substituindo vírgula por ponto
        $precoPraca = floatval(str_replace(',', '.', $rawPrecoPraca));
        $indice = floatval(str_replace(',', '.', $rawIndice));

        // Calcula preço líquido: preço da praça * (1 + índice)
        $precoLiquido = $precoPraca * (1 + $indice);

        // Atualiza o campo no formulário, arredondando para 2 casas decimais
        $set('preco_liquido_saca_valorizado', round($precoLiquido, 2));
    }
}
