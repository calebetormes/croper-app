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
use App\Filament\Resources\NegociacaoResource\Forms\Sections\NegociacaoProdutosSectionForm;

use App\Filament\Resources\NegociacaoResource\RelationManagers\NegociacaoProdutosRelationManager;
use App\Models\Negociacao;
use App\Models\StatusNegociacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tab;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Produto;


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
            Tabs::make('Negociação')
                ->tabs([
                    Tabs\Tab::make('Informações Básicas')
                        ->schema([
                            BasicInformationSectionForm::make(),
                            ClientInformationSectionForm::make(),
                        ]),

                    Tabs\Tab::make('Pagamentos e Cotações')
                        ->schema([
                            PagamentosSectionForm::make(),
                            CotacoesSectionForm::make(),
                        ]),

                    Tabs\Tab::make('Produtos')
                        ->schema([
                            NegociacaoProdutosSectionForm::make(),
                        ]),

                    Tabs\Tab::make('Valores e Status')
                        ->schema([
                            ValoresSectionForm::make(),
                            StatusValidacoesSectionForm::make(),
                            StatusGeralSectionForm::make(),
                        ]),


                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('data_negocio')
                    ->sortable()
                    ->label('Data negócio')
                    ->date('d/m/Y'),
                TextColumn::make('pedido_id')->label('ID do Pedido')->sortable(),
                TextColumn::make('gerente.name')->label('GRV')->sortable(),
                TextColumn::make('vendedor.name')->label('RTV')->sortable(),
                TextColumn::make('cliente')->searchable(),
                TextColumn::make('cultura.nome')->label('Cultura')->sortable(),
                TextColumn::make('status_negociacao.nome')->label('Status')->sortable(),
                TextColumn::make('status_negociacao.nome')->label('Status')->sortable(),
            ])
            ->filters([
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

    /*
        public static function getRelations(): array
    {
        return [
            NegociacaoProdutosRelationManager::class,
        ];
    }
    */



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
        // continua setando o status “Em análise” normalmente
        $data['status_negociacao_id'] ??= StatusNegociacao::where('nome', 'Em análise')->value('id');
        return $data;
    }

    /**
     * 1) Este hook é executado ANTES do Livewire “encher” o formulário (tanto no Create quanto no Edit).
     * 2) Se já existir data_atualizacao_snap_preco_praca_cotacao > 3 dias atrás, então zera o campo praca_cotacao_id.
     */
    protected static function mutateFormDataBeforeFill(array $data): array
    {

        dd('FOI AQUI');
        if (!empty($data['data_atualizacao_snap_preco_praca_cotacao'])) {
            $selectedDate = Carbon::parse($data['data_atualizacao_snap_preco_praca_cotacao']);
            $limite = Carbon::now()->subDays(3)->startOfDay();

            if ($selectedDate->lt($limite)) {
                // Se a data for mais antiga que (hoje - 3 dias), zera a praça antes de renderizar o form
                $data['praca_cotacao_id'] = null;
            }
        }

        return $data;
    }
}
