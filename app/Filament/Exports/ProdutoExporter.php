<?php

namespace App\Filament\Exports;

use App\Models\Produto;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ProdutoExporter extends Exporter
{
    /**
     * O model que este exporter irá processar.
     */
    protected static ?string $model = Produto::class;

    /**
     * Define as colunas e como cada valor será extraído e formatado.
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('classe.nome')
                ->label('Classe'),

            ExportColumn::make('principioAtivo.nome')
                ->label('Princípio Ativo'),

            ExportColumn::make('marcaComercial.nome')
                ->label('Marca Comercial'),

            ExportColumn::make('unidadePeso.sigla')
                ->label('Unidade de Peso'),

            ExportColumn::make('familia.nome')
                ->label('Família'),

            ExportColumn::make('apresentacao')
                ->label('Apresentação'),

            ExportColumn::make('dose_sugerida_hectare')
                ->label('Dose Sugerida (ha)'),

            ExportColumn::make('preco_rs')
                ->label('Preço R$'),

            ExportColumn::make('preco_us')
                ->label('Preço US$'),

            ExportColumn::make('custo_rs')
                ->label('Custo R$'),

            ExportColumn::make('custo_us')
                ->label('Custo US$'),

            ExportColumn::make('indice_valorizacao_produto')
                ->label('Fator Multiplicador'),
        ];
    }

    /**
     * Nome do arquivo gerado.
     */
    public function getFileName(Export $export): string
    {
        return 'produtos_export.csv';
    }

    /**
     * Delimitador CSV (vírgula).
     */
    public static function getCsvDelimiter(): string
    {
        return ',';
    }

    /**
     * Título da notificação ao concluir o export.
     */
    public static function getCompletedNotificationTitle(Export $export): string
    {
        return 'Exportação de Produtos Concluída';
    }

    /**
     * Corpo da notificação ao concluir o export.
     */
    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Exportação concluída com sucesso.';
    }
}
