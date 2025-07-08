<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PracaCotacaoResource\Pages;
use App\Filament\Resources\PracaCotacaoResource\RelationManagers;
use App\Models\PracaCotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use App\Models\Moeda;
use App\Models\Cultura;
use Filament\Navigation\NavigationItem;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get;
use Illuminate\Validation\Rule;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;


class PracaCotacaoResource extends Resource
{
    protected static ?string $model = PracaCotacao::class;

    protected static ?string $navigationLabel = 'PRAÇAS/COTAÇÕES';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['moeda', 'cultura']);
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->url(static::getUrl())
                ->icon(static::getNavigationIcon())
                ->group(static::getNavigationGroup())
                ->sort(static::getNavigationSort())
                ->visible(fn() => in_array(auth()->user()?->role_id, [4, 5])),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role_id, [4, 5]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cidade')
                    ->label('Cidade')
                    ->searchable()
                    ->options(collect(config('cidades'))->mapWithKeys(fn($cidade) => [$cidade => $cidade])->toArray())
                    ->required(),

                DatePicker::make('data_inclusao')
                    ->required()
                    ->default(now())
                    ->disabled()
                    ->dehydrated(true),

                DatePicker::make('data_vencimento')
                    ->required(),

                TextInput::make('praca_cotacao_preco')
                    ->label('Preço')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->prefix(function ($get) {
                        $moedaId = $get('moeda_id');
                        $moeda = Moeda::find($moedaId);
                        return $moeda?->sigla ?? '';
                    })
                    ->inputMode('decimal'),

                ToggleButtons::make('cultura_id')
                    ->label('Cultura')
                    ->options(
                        options: fn() => Cultura::pluck('nome', 'id')->toArray()
                    )
                    ->inline()
                    ->required(),

                ToggleButtons::make('moeda_id')
                    ->label('Moeda')
                    ->options(fn() => \App\Models\Moeda::all()->pluck('nome', 'id')->toArray())
                    ->inline()
                    ->reactive()
                    ->required()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cidade')
                    ->label('Cidade')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('praca_cotacao_preco')
                    ->label('Preço')
                    ->formatStateUsing(function ($state, $record) {
                        $sigla = $record->moeda->sigla ?? '';
                        return $sigla . ' ' . number_format($state, 2, ',', '.');
                    })
                    ->sortable(),

                TextColumn::make('data_inclusao')
                    ->label('Data de Inclusao')
                    ->date()
                    ->sortable(),

                TextColumn::make('data_vencimento')
                    ->label('Data de Vencimento')
                    ->date()
                    ->sortable(),

                TextColumn::make('cultura.nome')
                    ->label('Cultura')
                    ->sortable(),
            ])
            ->filters([
                //
                SelectFilter::make('moeda_id')
                    ->multiple()
                    ->label('Moeda')
                    ->relationship('moeda', 'sigla')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('Cultura')
                    ->multiple()
                    ->label('Cultura')
                    ->relationship('Cultura', 'nome')
                    ->preload()
                    ->searchable(),

                // opcional: ocupa toda a largura
                Filter::make('data_vencimento')
                    ->label('Data de Vencimento')
                    ->columns(2)
                    ->form([
                        DatePicker::make('from')
                            ->label('Data Vencimento'),
                        DatePicker::make('until')
                            ->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('data_vencimento', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('data_vencimento', '<=', $data['until']));
                    })
                    ->indicator(function (array $data): ?string {
                        if (!$data['from'] && !$data['until']) {
                            return null;
                        }

                        if ($data['from'] && $data['until']) {
                            return 'De ' . $data['from']->format('d/m/Y')
                                . ' até ' . $data['until']->format('d/m/Y');
                        }

                        return $data['from']
                            ? 'A partir de ' . $data['from']->format('d/m/Y')
                            : 'Até ' . $data['until']->format('d/m/Y');
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPracaCotacaos::route('/'),
            'create' => Pages\CreatePracaCotacao::route('/create'),
            'edit' => Pages\EditPracaCotacao::route('/{record}/edit'),
        ];
    }


}
