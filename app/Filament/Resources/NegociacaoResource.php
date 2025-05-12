<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NegociacaoResource\Pages;
use App\Models\Cultura;
use App\Models\Negociacao;
use App\Models\Pagamento;
use App\Models\PracaCotacao;
use App\Models\StatusNegociacao;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class NegociacaoResource extends Resource
{
    protected static ?string $model = Negociacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ----------------------------------------------------------------------------------------------------------------
                Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\DatePicker::make('data_versao')
                            ->Label('Data Versão')
                            ->default(now()) // Define a data atual
                            ->disabled()     // Impede edição
                            ->hidden()
                            ->dehydrated(),

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
                                    'Vendedor' => [$user->id => $user->name], // garante que aparece o nome
                                    'Gerente Comercial' => $user->vendedores()->pluck('name', 'id'),
                                    default => \App\Models\User::pluck('name', 'id'),
                                };
                            })
                            ->default(fn () => auth()->user()?->role?->name === 'Vendedor' ? auth()->id() : null)
                            ->disabled(fn () => auth()->user()?->role?->name === 'Vendedor')
                            ->searchable()
                            ->required()
                            ->dehydrated(),

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
                            ->required()
                            ->dehydrated(),

                    ])
                    ->columns(4),

                // ----------------------------------------------------------------------------------------------------------------
                Section::make('Informações do Cliente')
                    ->schema([

                        Forms\Components\TextInput::make('cliente')
                            ->label('Razão Social')
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\TextInput::make('endereco_cliente')
                            ->label('Endereço')
                            ->maxLength(255),

                        Select::make('cidade_cliente')
                            ->label('Município')
                            ->searchable()
                            ->options(collect(config('cidades'))->mapWithKeys(fn ($cidade) => [$cidade => $cidade])->toArray()),

                        TextInput::make('area_hectares')
                            ->label('Área (ha)')
                            ->numeric() // aceita apenas número válido
                            ->placeholder('Ex: 12.5')
                            ->required(),
                    ])
                    ->columns(3),

                // ----------------------------------------------------------------------------------------------------------------
                Section::make('Cotações')
                    ->schema([

                        ToggleButtons::make('cultura_id')
                            ->label('Cultura')
                            ->options(Cultura::pluck('nome', 'id')->toArray())
                            ->required()
                            ->inline()
                            ->reactive(),

                        Select::make('praca_cotacao_id')
                            ->label('Praça')
                            ->reactive()
                            ->options(fn ($get) => Cultura::find($get('cultura_id'))?->pracasCotacao
                                ->whereNotNull('cidade')
                                ->pluck('cidade', 'id')
                                ->toArray() ?? []
                            )
                            ->disabled(fn ($get) => ! $get('cultura_id'))
                            ->required()
                            ->searchable()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $cotacao = PracaCotacao::find($state);
                                $data = $cotacao?->data_vencimento
                                    ? Carbon::parse($cotacao->data_vencimento)->format('d/m/Y')
                                    : null;

                                $set('data_praca_vencimento', $data);
                                $set('snap_praca_cotacao_preco', $cotacao?->praca_cotacao_preco);
                            }),

                        TextInput::make('snap_praca_cotacao_preco')
                            ->label('Preço da Praça')
                            ->numeric()
                            ->required()
                            ->dehydrated()
                            ->reactive(),

                        Placeholder::make('data_praca_vencimento')
                            ->label('Data da Cotação')
                            ->content(function ($get) {
                                $cotacao = PracaCotacao::find($get('praca_cotacao_id'));

                                return $cotacao?->data_vencimento
                                    ? \Illuminate\Support\Carbon::parse($cotacao->data_vencimento)->format('d/m/Y')
                                    : 'Nenhuma cotação selecionada';
                            })
                            ->reactive(),

                        Hidden::make('snap_praca_cotacao_preco_fixado')
                            ->default(true)
                            ->dehydrated(), // garante que será salvo no banco

                        Actions::make([
                            Action::make('atualizar_preco_praca')
                                ->label('Atualizar Preço da Praça')
                                ->color('primary')
                                ->icon('heroicon-o-arrow-path') // opcional, ícone de atualização
                                ->visible(fn ($get) => $get('praca_cotacao_id')) // só exibe se tiver praça selecionada
                                ->action(function ($get, $set) {
                                    $cotacao = PracaCotacao::find($get('praca_cotacao_id'));
                                    if ($cotacao) {
                                        $set('snap_praca_cotacao_preco', $cotacao->praca_cotacao_preco);
                                        $set('data_atualizacao_snap_preco_praca_cotacao', date('Y-m-d'));
                                    }
                                }),
                        ]),

                        DatePicker::make('data_atualizacao_snap_preco_praca_cotacao')
                            ->label('Preço fixado no dia')
                            ->disabled()
                            ->dehydrated()
                            ->reactive(),

                    ])
                    ->columns(4),
                // ----------------------------------------------------------------------------------------------------------------
                Section::make('Pagamentos')
                    ->schema([
                        Select::make('pagamento_id')
                            ->label('Data de Pagamento')
                            ->options(
                                Pagamento::all()
                                    ->pluck('data_pagamento', 'id')
                                    ->mapWithKeys(fn ($value, $key) => [$key => \Carbon\Carbon::parse($value)->format('d/m/Y')])
                                    ->toArray()
                            )
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $pagamento = Pagamento::find($state);
                                $set('data_entrega_graos', $pagamento?->data_entrega);
                            }),

                        DatePicker::make('data_entrega_graos')
                            ->label('Data de Entrega dos Grãos')
                            ->required()
                            ->reactive()
                            ->disabled()
                            ->dehydrated(),

                    ])
                    ->columns(2),
                // ----------------------------------------------------------------------------------------------------------------
                Section::make('Valores')
                    ->schema([

                        // formula
                        Forms\Components\TextInput::make('valor_total_com_bonus')
                            ->required()
                            ->numeric(),

                        // formula
                        Forms\Components\TextInput::make('investimento_sacas_hectare')
                            ->numeric(),

                        // formula
                        Forms\Components\TextInput::make('investimento_total_sacas')
                            ->numeric(),

                        // formula
                        Forms\Components\TextInput::make('preco_liquido_saca')
                            ->numeric(),

                        // formula
                        Forms\Components\TextInput::make('bonus_cliente_pacote')
                            ->numeric(),

                        // formula
                        Forms\Components\TextInput::make('valor_total_sem_bonus')
                            ->numeric(),

                    ])
                    ->columns(4),

                Section::make('Status e Validações')
                    ->schema([
                        Select::make('nivel_validacao_id')
                            ->label('Que podera validar')
                            ->options([
                                1 => 'Vendedor',
                                2 => 'Gerente Comercial',
                                3 => 'Gerente Nacional',
                                4 => 'Admin',
                            ])
                            ->default(4)
                            ->searchable()
                            ->required()
                            ->disabled(fn (Get $get): bool =>
                                // Vendedor nunca edita
                                auth()->user()->role_id === 1
                                // bloqueia quem for de nível inferior ao nível de validação
                                || auth()->user()->role_id < $get('nivel_validacao_id')
                                // Admin (4) bloqueia apenas quando nível for Gerente Nacional (3)
                                || (auth()->user()->role_id === 4 && $get('nivel_validacao_id') === 3)
                            )
                            ->dehydrated(),

                        ToggleButtons::make('...')
                            ->reactive()
                            ->label('Status de Validação')
                            ->options([
                                0 => 'Aguardando',
                                1 => 'Aprovado',
                            ])
                            ->colors([
                                0 => 'warning',
                                1 => 'success',
                            ])
                            ->default(0)
                            ->inline()
                            ->dehydrated()
                            ->disabled(fn (Get $get): bool =>
                                // Vendedor nunca edita
                                auth()->user()->role_id === 1
                                // bloqueia quem for de nível inferior ao nível de validação
                                || auth()->user()->role_id < $get('nivel_validacao_id')
                                // Admin (4) bloqueia apenas quando nível for Gerente Nacional (3)
                                || (auth()->user()->role_id === 4 && $get('nivel_validacao_id') === 3)
                            ),

                        Placeholder::make('status_defensivos_label')
                            ->label('Quantidade Minima de Defensivos')
                            ->content(fn (Get $get) => $get('status_defensivos') == 1
                                    ? 'Atingido'
                                    : '⚠️ Adicione mais defensivos à sua negociação'
                            )
                            ->extraAttributes(fn (Get $get) => [
                                'class' => 'inline-block px-3 py-1 rounded-full text-sm font-medium '.
                                    ($get('status_defensivos') == 1
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'),
                            ]),

                        Placeholder::make('status_especialidades_label')
                            ->label('Quantidade Minima de Especialidades')
                            ->content(fn (Get $get) => $get('status_especialidades') > 3
                                    ? '✅ Atingido'
                                    : '⚠️ Adicione mais especialidades à sua negociação'
                            )
                            ->extraAttributes(fn (Get $get) => [
                                'class' => $get('status_especialidades') > 3
                                    ? 'text-green-500 font-semibold'
                                    : 'text-yellow-400 font-semibold',
                            ]),
                    ])
                    ->columns(4),

                Section::make('Status Geral')
                    ->schema([

                        ToggleButtons::make('status_negociacao_id')
                            ->label('Status da Negociação')
                            ->options(
                                StatusNegociacao::where('ativo', true)
                                    ->orderBy('ordem')
                                    ->pluck('nome', 'id')
                                    ->toArray()
                            )
                            ->colors([
                                StatusNegociacao::where('nome', 'Em análise')->value('id') => 'warning',
                                StatusNegociacao::where('nome', 'Aprovado')->value('id') => 'success',
                                StatusNegociacao::where('nome', 'Não Aprovado')->value('id') => 'danger',
                                StatusNegociacao::where('nome', 'Pausada')->value('id') => 'warning',
                                StatusNegociacao::where('nome', 'Pagamento Recebido')->value('id') => 'success',
                                StatusNegociacao::where('nome', 'Entrega de Grãos Realizada')->value('id') => 'success',
                                StatusNegociacao::where('nome', 'Concluído')->value('id') => 'gray',
                                // 'Rascunho' não incluso: ficará sem cor
                            ])
                            ->inline()
                            ->required()
                            ->default(StatusNegociacao::where('nome', 'Em análise')->value('id'))
                            ->reactive()
                            ->disabled(fn () => ! in_array(auth()->user()?->role_id, [3, 4]))
                            ->dehydrated(),

                        Forms\Components\Textarea::make('observacoes')
                            ->columnSpanFull(),

                    ]),

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('data_negocio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gerente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendedor_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cultura_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_negociacao_id')
                    ->numeric()
                    ->sortable(),

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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();
        $role = $user?->role?->name;

        return match ($role) {
            'Vendedor' => parent::getEloquentQuery()
                ->where('vendedor_id', $user->id),

            'Gerente Comercial' => parent::getEloquentQuery()
                ->whereIn('vendedor_id', $user->vendedores()->pluck('id')),

            default => parent::getEloquentQuery(), // Gerente Nacional e Admin veem tudo
        };
    }
}
