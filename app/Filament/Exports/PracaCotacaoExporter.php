<?php

namespace App\Filament\Exports;

use App\Models\PracaCotacao;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Carbon;

class PracaCotacaoExporter extends Exporter
{
    /**
     * O model que este exporter irá processar.
     */
    protected static ?string $model = PracaCotacao::class;

    /**
     * Define as colunas e como cada valor será extraído e formatado.
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('moeda.nome')
                ->label('moeda'),

            ExportColumn::make('praca_cotacao_preco')
                ->label('preco'),

            ExportColumn::make('cidade'),

            ExportColumn::make('cultura.nome')
                ->label('cultura'),

            ExportColumn::make('data_vencimento')
                ->label('vencimento')
                ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y')),
        ];
    }

    /**
     * Nome do arquivo gerado.
     */
    public function getFileName(Export $export): string
    {
        return 'pracas_cotacao_export.csv';
    }

    /**
     * Delimitador CSV (vírgula).
     */
    public static function getCsvDelimiter(): string
    {
        return ',';
    }

    /**
     * Corpo da notificação ao concluir o export.
     */
    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Exportação concluída com sucesso.';
    }

    /**
     * Título da notificação ao concluir o export.
     */
    public static function getCompletedNotificationTitle(Export $export): string
    {
        return 'Exportação de Praças Concluída';
    }
}
