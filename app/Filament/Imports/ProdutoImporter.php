<?php

namespace App\Filament\Imports;

use App\Models\Produto;
use App\Models\ProdutoClasse;
use App\Models\PrincipioAtivo;
use App\Models\MarcaComercial;
use App\Models\UnidadePeso;
use App\Models\Familia;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;

class ProdutoImporter extends Importer
{
    /**
     * O modelo que será populado em cada linha do import.
     */
    protected static ?string $model = Produto::class;

    /**
     * Definição das colunas do import, mapeamento e validações.
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('classe_id')
                ->label('Classe')
                ->guess(['classe'])
                ->castStateUsing(fn(string $state): int => ProdutoClasse::firstOrCreate(['nome' => $state])->id)
                ->requiredMapping(),

            ImportColumn::make('principio_ativo_id')
                ->label('Princípio Ativo')
                ->guess(['principio_ativo', 'ativo'])
                ->castStateUsing(fn(string $state): int => PrincipioAtivo::firstOrCreate(['nome' => $state])->id)
                ->requiredMapping(),

            ImportColumn::make('marca_comercial_id')
                ->label('Marca Comercial')
                ->guess(['marca', 'marca_comercial'])
                ->castStateUsing(fn(string $state): int => MarcaComercial::firstOrCreate(['nome' => $state])->id)
                ->requiredMapping(),

            ImportColumn::make('tipo_peso_id')
                ->label('Unidade de Peso')
                ->guess(['peso', 'unidade'])
                ->castStateUsing(fn(string $state): int => UnidadePeso::firstOrCreate(['sigla' => $state])->id)
                ->requiredMapping(),

            ImportColumn::make('familia_id')
                ->label('Família')
                ->guess(['familia'])
                ->castStateUsing(fn(string $state): int => Familia::firstOrCreate(['nome' => $state])->id)
                ->requiredMapping(),

            ImportColumn::make('apresentacao')
                ->label('Apresentação')
                ->guess(['apresentacao'])
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('dose_sugerida_hectare')
                ->label('Dose Sugerida (ha)')
                ->guess(['dose', 'dose_sugerida'])
                ->numeric()
                ->rules(['nullable', 'numeric']),

            ImportColumn::make('preco_rs')
                ->label('Preço R$')
                ->guess(['preço_rs', 'preco_rs', 'preço'])
                ->numeric()
                ->rules(['nullable', 'numeric']),

            ImportColumn::make('preco_us')
                ->label('Preço US$')
                ->guess(['preço_us', 'preco_us'])
                ->numeric()
                ->rules(['nullable', 'numeric']),

            ImportColumn::make('custo_rs')
                ->label('Custo R$')
                ->guess(['custo_rs', 'custo'])
                ->numeric()
                ->rules(['nullable', 'numeric']),

            ImportColumn::make('custo_us')
                ->label('Custo US$')
                ->guess(['custo_us'])
                ->numeric()
                ->rules(['nullable', 'numeric']),

            ImportColumn::make('indice_valorizacao_produto')
                ->label('Índice de Valorização do Produto')
                ->guess(['fator', 'indice'])
                ->numeric()
                ->rules(['nullable', 'numeric']),
        ];
    }

    /**
     * Busca ou inicia um novo registro para evitar duplicatas.
     */
    public function resolveRecord(): ?Produto
    {
        return Produto::firstOrNew([
            'classe_id' => $this->data['classe_id'],
            'principio_ativo_id' => $this->data['principio_ativo_id'],
            'marca_comercial_id' => $this->data['marca_comercial_id'],
            'apresentacao' => $this->data['apresentacao'],
        ]);
    }

    /**
     * Chama o save padrão para persistir os dados.
     */
    public function saveRecord(): void
    {
        parent::saveRecord();
    }

    /**
     * Título da notificação ao concluir import.
     */
    public static function getCompletedNotificationTitle(\Filament\Actions\Imports\Models\Import $import): string
    {
        return 'Importação de Produtos Concluída';
    }

    /**
     * Corpo da notificação ao concluir import.
     */
    public static function getCompletedNotificationBody(\Filament\Actions\Imports\Models\Import $import): string
    {
        return "Foram importadas {$import->successful_rows} linhas com sucesso.";
    }
}
