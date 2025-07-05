<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NegociacaoResource\Pages;
use App\Models\Negociacao;
use App\Models\StatusNegociacao;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Infolist;
use App\Filament\Resources\NegociacaoResource\Schemas\FormSchema;
use App\Filament\Resources\NegociacaoResource\Schemas\TableSchema;
use App\Filament\Resources\NegociacaoResource\Schemas\InfolistSchema;
use Carbon\Carbon;


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
        return $form
            ->schema(
                FormSchema::make()
            );
    }

    public static function table(Table $table): Table
    {
        return TableSchema::make($table);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return InfolistSchema::make($infolist);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNegociacaos::route('/'),
            'create' => Pages\CreateNegociacao::route('/create'),
            'edit' => Pages\EditNegociacao::route('/{record}/edit'),
            'view' => Pages\ViewNegociacao::route('/{record}'),
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
