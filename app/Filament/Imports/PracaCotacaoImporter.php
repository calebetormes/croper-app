<?php

namespace App\Filament\Imports;

use App\Models\PracaCotacao;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import as ImportModel;
use Illuminate\Support\Carbon;

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
                ->relationship('moeda', 'nome')
                ->requiredMapping(),

            ImportColumn::make('cultura_id')
                ->label('Cultura')
                ->guess(['cultura'])
                ->relationship('cultura', 'nome')
                ->requiredMapping(),

            ImportColumn::make('fator_valorizacao')
                ->label('Fator de Valorização')
                ->guess(['fator_valorizacao'])
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric']),
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
     * Executado quando cada registro é salvo. Aqui adicionamos data_inclusao automático.
     */
    public function saveRecord(): void
    {
        $this->record->data_inclusao = Carbon::now();

        parent::saveRecord();
    }

    /**
     * Corpo da notificação ao concluir o import.
     */
    public static function getCompletedNotificationBody(ImportModel $import): string
    {
        return 'A importação foi concluída com sucesso.';
    }

    /**
     * Título da notificação ao concluir o import.
     */
    public static function getCompletedNotificationTitle(ImportModel $import): string
    {
        return 'Importação de Praças Concluída';
    }
}
