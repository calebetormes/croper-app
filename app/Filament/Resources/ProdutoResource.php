<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Models\Produto;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Navigation\NavigationItem;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    //protected static ?string $navigationGroup = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'PRODUTOS';

    protected static ?int $navigationSort = 1;

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
                Select::make('classe_id')
                    ->relationship('classe', 'nome')
                    ->label('Classe')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('principio_ativo_id')
                    ->relationship('principioAtivo', 'nome')
                    ->label('Princípio Ativo')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('marca_comercial_id')
                    ->relationship('marcaComercial', 'nome')
                    ->label('Marca Comercial')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tipo_peso_id')
                    ->relationship('unidadePeso', 'sigla')
                    ->label('Unidade de Peso')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('familia_id')
                    ->relationship('familia', 'nome')
                    ->label('Família')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('apresentacao')
                    ->label('Apresentação')
                    ->required()
                    ->maxLength(255),

                TextInput::make('dose_sugerida_hectare')
                    ->label('Dose Sugerida (ha)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('preco_rs')
                    ->label('Preço (R$)')
                    ->numeric()
                    ->required(),

                TextInput::make('preco_us')
                    ->label('Preço (US$)')
                    ->numeric()
                    ->required(),


                TextInput::make('custo_rs')
                    ->label('Custo (R$)')
                    ->numeric()
                    ->required(),

                TextInput::make('custo_us')
                    ->label('Custo (U$)')
                    ->numeric()
                    ->required(),

                TextInput::make('fator_multiplicador')
                    ->label('Fator Multiplicador')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([

            ])
            ->columns([
                TextColumn::make('classe.nome')
                    ->label('Classe')
                    ->sortable(),

                TextColumn::make('principioAtivo.nome')
                    ->label('Princípio Ativo')
                    ->sortable(),

                TextColumn::make('marcaComercial.nome')
                    ->label('Marca')
                    ->sortable(),

                TextColumn::make('familia.nome')
                    ->label('Família')
                    ->sortable(),

                TextColumn::make('apresentacao')
                    ->label('Apresentação')
                    ->searchable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProdutos::route('/'),
        ];
    }
}
