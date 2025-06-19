<?php

namespace App\Filament\Imports;

use App\Models\PracaCotacao;
use App\Models\Moeda;
use App\Models\Cultura;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Illuminate\Support\Carbon;

class PracaCotacaoImporter extends Importer
{
    /**
     * O model que será criado ou atualizado para cada linha.
     */
    protected static ?string $model = PracaCotacao::class;

    /**
     * Define colunas de importação, mapeamento e validação.
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
        ];
    }

    /**
     * Busca ou cria registro para evitar duplicatas e permitir atualização.
     */
    public function resolveRecord(): ?PracaCotacao
    {
        $rawDate = $this->data['vencimento'] ?? $this->data['data_vencimento'];
        $date = Carbon::createFromFormat('d/m/Y', $rawDate)->toDateString();

        $moedaId = Moeda::where('nome', $this->data['moeda'])->value('id');
        $culturaId = Cultura::where('nome', $this->data['cultura'])->value('id');

        return PracaCotacao::firstOrNew([
            'cidade' => $this->data['cidade'],
            'data_vencimento' => $date,
            'moeda_id' => $moedaId,
            'cultura_id' => $culturaId,
        ]);
    }

    /**
     * Formata datas e define data_inclusao para novos registros.
     */
    public function saveRecord(): void
    {
        $rawDate = $this->data['vencimento'] ?? $this->data['data_vencimento'];
        $this->record->data_vencimento = Carbon::createFromFormat('d/m/Y', $rawDate)->toDateString();

        if (!$this->record->exists) {
            $this->record->data_inclusao = Carbon::now();
        }

        parent::saveRecord();
    }

    /**
     * Texto da notificação após concluir a importação.
     */
    public static function getCompletedNotificationBody(\Filament\Actions\Imports\Models\Import $import): string
    {
        return 'A importação foi concluída com sucesso.';
    }

    /**
     * Título da notificação após concluir a importação.
     */
    public static function getCompletedNotificationTitle(\Filament\Actions\Imports\Models\Import $import): string
    {
        return 'Importação de Praças Concluída';
    }
}
