<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\TextColumn;

class NegociacoesGerenciadasRelationManager extends RelationManager
{
    /**
     * Nome do relacionamento no Model User.
     */
    protected static string $relationship = 'negociacoesGerenciadas';

    /**
     * Atributo usado para o título de cada registro.
     */
    protected static ?string $recordTitleAttribute = 'cliente';

    /**
     * Aqui a assinatura correta (não estática).
     */
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->searchable(),

                DateColumn::make('data_negocio')
                    ->label('Data do Negócio')
                    ->sortable(),

                TextColumn::make('statusNegociacao.nome')
                    ->label('Status')
                    ->sortable(),
            ])
            ->headerActions([
                LinkAction::make('create')
                    ->label('Nova Negociação')
                    ->icon('heroicon-o-plus')
                    ->url(fn (): string => NegociacaoResource::getUrl('create')),
            ])
            ->actions([
                LinkAction::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => NegociacaoResource::getUrl('view', ['record' => $record])),

                LinkAction::make('edit')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->url(fn ($record): string => NegociacaoResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
