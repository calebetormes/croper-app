<?php

namespace App\Filament\Imports;

use App\Models\PracaCotacao;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * PracaCotacaoImporter
 *
 * Atenção: certifique-se de que as tabelas de Moeda e Cultura
 * já possuam todos os valores usados no CSV (ex: 'Trigo').
 * Se faltar algum, adicione-os antes de importar o CSV.
 */
class PracaCotacaoImporter extends Importer
{
    /**
     * O model que será criado para cada linha do CSV.
     */
    protected static ?string $model = PracaCotacao::class;

    /**
     * Define as colunas para importação, mapeamento e validação.
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('cidade')
                ->label('Cidade')
                ->guess(['cidade'])
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('praca_cotacao_preco')
                ->label('Preço')
                ->guess(['preco'])
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric']),

            ImportColumn::make('data_vencimento')
                ->label('Data de Vencimento')
                ->guess(['vencimento'])
                ->requiredMapping()
                ->rules(['required', 'date_format:d/m/Y']),

            ImportColumn::make('moeda_id')
                ->label('Moeda')
                ->guess(['moeda'])
                ->requiredMapping()
                ->relationship('moeda', 'nome'),

            ImportColumn::make('cultura_id')
                ->label('Cultura')
                ->guess(['cultura'])
                ->requiredMapping()
                ->relationship('cultura', 'nome'),
        ];
    }

    /**
     * Instancia um novo registro para cada linha importada.
     */
    public function resolveRecord(): ?PracaCotacao
    {
        return new PracaCotacao();
    }

    /**
     * Executado quando cada registro é salvo. Aqui parseamos a data e adicionamos data_inclusao.
     */
    public function saveRecord(): void
    {
        // Parse da data de vencimento "d/m/Y" para formato Y-m-d
        $rawDate = $this->record->data_vencimento;
        $this->record->data_vencimento = Carbon::createFromFormat('d/m/Y', $rawDate)->toDateString();

        // Preenche data de inclusão com timestamp atual
        $this->record->data_inclusao = Carbon::now();

        parent::saveRecord();
    }

    /**
     * Corpo da notificação ao concluir o import.
     */
    public static function getCompletedNotificationBody(\Filament\Actions\Imports\Models\Import $import): string
    {
        return 'A importação foi concluída com sucesso.';
    }

    /**
     * Título da notificação ao concluir o import.
     */
    public static function getCompletedNotificationTitle(\Filament\Actions\Imports\Models\Import $import): string
    {
        return 'Importação de Praças Concluída';
    }
}
