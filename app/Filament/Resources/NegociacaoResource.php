<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NegociacaoResource\Forms\Sections\BasicInformationSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ClientInformationSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\CotacoesSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\PagamentosSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ProdutosSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\StatusGeralSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\StatusValidacoesSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ValoresSectionForm;
use App\Filament\Resources\NegociacaoResource\Pages;
use App\Filament\Resources\NegociacaoResource\RelationManagers\NegociacaoProdutosRelationManager;
use App\Models\Negociacao;
use App\Models\StatusNegociacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // já deve existir

class NegociacaoResource extends Resource
{
    protected static ?string $model = Negociacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'NEGOCIAÇÕES';

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->url(static::getUrl())
                ->icon(static::getNavigationIcon())
                ->group(static::getNavigationGroup())
                ->sort(static::getNavigationSort())
                ->visible(fn() => in_array(auth()->user()?->role_id, [1, 2, 3, 4, 5])),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role_id, [1, 2, 3, 4, 5]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            BasicInformationSectionForm::make(),
            ClientInformationSectionForm::make(),
            CotacoesSectionForm::make(),
            PagamentosSectionForm::make(),
            ValoresSectionForm::make(),
            StatusValidacoesSectionForm::make(),
            StatusGeralSectionForm::make(),
            // ProdutosSectionForm::make(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('data_negocio')
                    ->sortable()
                    ->label('Data negócio')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('gerente.name')->label('GRV')->sortable(),
                Tables\Columns\TextColumn::make('vendedor.name')->label('RTV')->sortable(),
                Tables\Columns\TextColumn::make('cliente')->searchable(),
                Tables\Columns\TextColumn::make('cultura.nome')->label('Cultura')->sortable(),
                Tables\Columns\TextColumn::make('status_negociacao.nome')->label('Status')->sortable(),
            ])
            ->filters([
                // filtro de intervalo de datas
                Filter::make('data_negocio')
                    ->label('Data')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('De'),
                        Forms\Components\DatePicker::make('until')->label('Até'),
                    ])
                    ->query(
                        fn($query, array $data) => $query
                            ->when($data['from'], fn($q) => $q->whereDate('data_negocio', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('data_negocio', '<=', $data['until']))
                    ),

                // filtro por gerente
                SelectFilter::make('gerente_id')
                    ->multiple()
                    ->label('Gerente')
                    ->relationship('gerente', 'name')
                    // ->preload()
                    ->searchable(),

                // filtro por vendedor
                SelectFilter::make('vendedor_id')
                    ->multiple()
                    ->label('Vendedor')
                    ->relationship('vendedor', 'name')
                    ->searchable(),

                // filtro por status
                SelectFilter::make('status_negociacao_id')
                    ->label('Status')
                    ->relationship('statusNegociacao', 'nome')
                    ->searchable()
                    ->multiple()
                    ->preload(),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
            ])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->iconButton(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                // Se você tiver RelationManagers, inclua aqui
            NegociacaoProdutosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNegociacaos::route('/'),
            'create' => Pages\CreateNegociacao::route('/create'),
            'edit' => Pages\EditNegociacao::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $role = $user?->role?->name;

        return match ($role) {
            'Vendedor' => parent::getEloquentQuery()->where('vendedor_id', $user->id),
            'Gerente Comercial' => parent::getEloquentQuery()->whereIn('vendedor_id', $user->vendedores()->pluck('id')),
            default => parent::getEloquentQuery(),
        };
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status_negociacao_id'] ??= StatusNegociacao::where('nome', 'Em análise')->value('id');

        return $data;
    }
}
