<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker as FormDatePicker;
use Filament\Tables\Actions\Action as TableAction;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\StatusGeralSectionForm;
use App\Models\Negociacao;
use App\Models\StatusNegociacao;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\DadosBasicosInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\PracaCotacaoInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\ProdutosInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\StatusInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\ValoresInfolist;
use App\Filament\Resources\NegociacaoResource;
use App\Filament\Resources\NegociacaoResource\InfolistSchema;






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
                TextColumn::make('vendedor.name')
                    ->label('RTV')
                    ->sortable(),
                TextColumn::make('cliente')
                    ->searchable(),
                TextColumn::make('statusNegociacao.nome')
                    ->label('Status')
                    ->sortable(),

                TextColumn::make('statusNegociacao.nome')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->colors([
                        // Definir cor com base no texto do status
                        'warning' => static fn($state): bool => in_array($state, ['Em análise', 'Pausada']),
                        'success' => static fn($state): bool => in_array($state, ['Aprovado', 'Pagamento Recebido', 'Entrega de Grãos Realizada']),
                        'danger' => static fn($state): bool => $state === 'Não Aprovado',
                        'gray' => static fn($state): bool => $state === 'Concluído',
                    ]),
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
                EditAction::make()
                    ->label(''),

                ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->slideOver()
                    ->modalWidth('xl')
                    ->visible(fn(): bool => in_array(
                        Auth::user()->role?->name,
                        ['Gerente Nacional', 'Admin']
                    )),

                TableAction::make('changeStatus')
                    ->label('')
                    ->icon('heroicon-o-adjustments-vertical')

                    // injeta aqui o Section pronto:
                    ->form([
                        StatusGeralSectionForm::make(),
                    ])
                    ->action(function (Negociacao $record, array $data) {
                        $record->fill($data)->save();
                        Notification::make()
                            ->title('Status atualizado com sucesso!')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Alterar Status da Negociação')
                    ->visible(fn(): bool => in_array(
                        Auth::user()->role?->name,
                        ['Gerente Nacional', 'Admin']
                    ))
                    ->modalButton('Salvar'),

                Action::make('gerar_relatorio')
                    ->label('')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->tooltip('Gerar Relatório em PDF')
                    ->url(fn(Negociacao $record): string => route('negociacoes.pdf', $record->id))
                    ->openUrlInNewTab(),


            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
