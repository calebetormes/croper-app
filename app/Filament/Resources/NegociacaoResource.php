<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NegociacaoResource\Pages;
use App\Models\Negociacao;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NegociacaoResource extends Resource
{
    protected static ?string $model = Negociacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\DatePicker::make('data_versao')
                            ->Label('Data Versão')
                            ->default(now()) // Define a data atual
                            ->disabled()     // Impede edição
                            ->required()
                            ->hidden(),

                        Forms\Components\DatePicker::make('data_negocio')
                            ->Label('Data da Negociação')
                            ->default(now()) // Define a data atual
                            ->required(),

                        ToggleButtons::make('moeda_id')
                            ->label('Moeda')
                            ->options(\App\Models\Moeda::all()->pluck('sigla', 'id')->toArray())
                            ->required()
                            ->inline(),

                        // REGRAS PARA SELEÇÃO DE VENDEDORES
                        Select::make('vendedor_id')
                            ->label('RTV')
                            ->options(function () {
                                $user = auth()->user();
                                $role = $user?->role?->name;

                                return match ($role) {
                                    'Vendedor' => User::where('id', $user->id)
                                        ->pluck('name', 'id'),

                                    'Gerente Comercial' => $user->vendedores()
                                        ->pluck('name', 'id'),

                                    default => \App\Models\User::pluck('name', 'id'),
                                };
                            })
                            ->default(fn () => auth()->user()?->role?->name === 'Vendedor' ? auth()->id() : null)
                            ->disabled(fn () => auth()->user()?->role?->name === 'Vendedor')
                            ->searchable()
                            ->required(),

                        // REGRAS PARA SELEÇÃO DE GERENTE
                        Select::make('gerente_id')
                            ->label('Gerente')
                            ->options(function () {
                                $user = auth()->user();
                                $role = $user?->role?->name;

                                return match ($role) {
                                    'Vendedor' => $user->gerentes()->pluck('name', 'id'),
                                    'Gerente Comercial' => [$user->id => $user->name],
                                    default => User::whereRelation('role', 'name', 'Gerente Comercial')->pluck('name', 'id'),
                                };
                            })
                            ->default(function () {
                                $user = auth()->user();

                                return match ($user?->role?->name) {
                                    'Vendedor' => $user->gerentes()->value('id'),
                                    'Gerente Comercial' => $user->id,
                                    default => null,
                                };
                            })
                            ->disabled(function () { // desabilita alteração se o usuário for gerente comercial
                                $role = auth()->user()?->role?->name;

                                return match ($role) {
                                    'Vendedor', 'Gerente Comercial' => true,
                                    default => false,
                                };
                            })
                            ->searchable()
                            ->required(),

                    ])
                    ->columns(4),

                Section::make('Relacionamentos')
                    ->schema([
                        Forms\Components\TextInput::make('gerente_id')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('cliente')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('endereco_cliente')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cidade_cliente')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cultura_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('praca_cotacao_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('pagamento_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\DatePicker::make('data_entrega_graos')
                            ->required(),
                        Forms\Components\TextInput::make('valor_total_com_bonus')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('area_hectares')
                            ->numeric(),
                        Forms\Components\TextInput::make('investimento_sacas_hectare')
                            ->numeric(),
                        Forms\Components\TextInput::make('investimento_total_sacas')
                            ->numeric(),
                        Forms\Components\TextInput::make('preco_liquido_saca')
                            ->numeric(),
                        Forms\Components\TextInput::make('bonus_cliente_pacote')
                            ->numeric(),
                        Forms\Components\TextInput::make('valor_total_sem_bonus')
                            ->numeric(),
                        Forms\Components\TextInput::make('nivel_validacao_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\Toggle::make('status_validacao')
                            ->required(),
                        Forms\Components\Toggle::make('status_defensivos')
                            ->required(),
                        Forms\Components\Toggle::make('status_especialidades')
                            ->required(),
                        Forms\Components\TextInput::make('status_negociacao_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('snap_praca_cotacao_preco')
                            ->numeric(),
                        Forms\Components\Toggle::make('snap_praca_cotacao_preco_fixado')
                            ->required(),
                        Forms\Components\DatePicker::make('data_atualizacao_snap_preco_praca_cotacao'),
                        Forms\Components\Textarea::make('observacoes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('data_versao')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_negocio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('moeda_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gerente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendedor_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('endereco_cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cidade_cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cultura_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('praca_cotacao_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pagamento_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_entrega_graos')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor_total_com_bonus')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area_hectares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('investimento_sacas_hectare')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('investimento_total_sacas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preco_liquido_saca')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bonus_cliente_pacote')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor_total_sem_bonus')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nivel_validacao_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status_validacao')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status_defensivos')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status_especialidades')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status_negociacao_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('snap_praca_cotacao_preco')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('snap_praca_cotacao_preco_fixado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('data_atualizacao_snap_preco_praca_cotacao')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListNegociacaos::route('/'),
            'create' => Pages\CreateNegociacao::route('/create'),
            'edit' => Pages\EditNegociacao::route('/{record}/edit'),
        ];
    }
}
