<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker as FormDatePicker;

class TableSchema
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('data_negocio')
                    ->sortable()
                    ->label('Data negócio')
                    ->date('d/m/Y'),
                TextColumn::make('pedido_id')
                    ->label('ID do Pedido')
                    ->sortable(),
                TextColumn::make('gerente.name')
                    ->label('GRV')
                    ->sortable(),
                TextColumn::make('vendedor.name')
                    ->label('RTV')
                    ->sortable(),
                TextColumn::make('cliente')
                    ->searchable(),
                TextColumn::make('cultura.nome')
                    ->label('Cultura')
                    ->sortable(),
                TextColumn::make('status_negociacao.nome')
                    ->label('Status')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('data_negocio')
                    ->label('Data')
                    ->form([
                        FormDatePicker::make('from')->label('De'),
                        FormDatePicker::make('until')->label('Até'),
                    ])
                    ->query(
                        fn($query, array $data) => $query
                            ->when($data['from'], fn($q) => $q->whereDate('data_negocio', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('data_negocio', '<=', $data['until']))
                    ),

                SelectFilter::make('gerente_id')
                    ->multiple()
                    ->label('Gerente')
                    ->relationship('gerente', 'name')
                    ->searchable(),

                SelectFilter::make('vendedor_id')
                    ->multiple()
                    ->label('Vendedor')
                    ->relationship('vendedor', 'name')
                    ->searchable(),

                SelectFilter::make('status_negociacao_id')
                    ->multiple()
                    ->preload()
                    ->label('Status')
                    ->relationship('statusNegociacao', 'nome'),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make()
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->slideOver()
                    ->modalWidth('xl'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
