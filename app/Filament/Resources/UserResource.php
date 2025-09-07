<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;             // lá no topo
use Filament\Resources\Resource;             // lá no topo
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // rótulo no menu lateral

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'PESSOAL';

    // rótulo no título da página (Singular / Plural)
    protected static ?string $modelLabel = 'Pessoal';

    protected static ?string $pluralModelLabel = 'Pessoal';

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
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('E-mail')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->default(now()) // Define a data e hora atuais
                    ->hidden(), // Esconde o campo no formulário

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Senha')
                    //->disabled(fn ($record) => isset($record) && $record->exists)  // Verifica se o registro existe antes de desabilitar
                    //->dehydrated(fn ($state) => ! empty($state))  // Só atualiza a senha se o campo não estiver vazio
                    ->maxLength(255),

                Forms\Components\Select::make('role_id')
                    ->label('Função')
                    ->relationship('role', 'name') // Relacionamento com o papel do usuário
                    ->required()
                    ->reactive()
                    ->default(1)
                    // só Administrador e Gerente Nacional podem alterar o papel
                    ->disabled(
                        fn(): bool => !in_array(auth()->user()->role->name, ['Admin', 'Gerente Nacional'])
                    )
                    // opcional: tooltip explicando por que está bloqueado
                    ->helperText(
                        fn(): ?string => auth()->user()->role->name === 'Administrador' || auth()->user()->role->name === 'Gerente Nacional'
                        ? null
                        : 'Somente Administrador pode alterar este campo'
                    )
                    ->dehydrated(),

                Forms\Components\Textarea::make('observacoes')
                    ->columnSpanFull(),

                Forms\Components\MultiSelect::make('vendedores')
                    ->relationship('vendedores', 'name')
                    ->label('Vendedores')
                    ->reactive()
                    ->visible(fn($get) => in_array($get('role_id'), [2, 3, 4]))
                    ->relationship(
                        'vendedores',
                        'name',
                        fn($query) => $query
                            ->whereHas('role', fn($q) => $q->where('name', 'Vendedor'))
                    )
                    ->preload()
                    ->searchable()
                    ->dehydrated(),

                Forms\Components\MultiSelect::make('gerentes')
                    ->label('Gerente')
                    ->maxItems(1)
                    ->reactive()
                    ->visible(fn($get) => in_array($get('role_id'), [1]))

                    // só Administrador e Gerente Nacional podem alterar
                    ->disabled(
                        fn(): bool => !in_array(auth()->user()->role->name, ['Admin', 'Gerente Nacional'])
                    )
                    ->helperText(
                        fn(): ?string => auth()->user()->role->name === 'Administrador' || auth()->user()->role->name === 'Gerente Nacional'
                        ? null
                        : 'Somente Administrador pode alterar este campo'
                    )

                    // padrão: já seleciona o gerente comercial logado
                    ->default(fn(): array => [auth()->id()])

                    ->relationship(
                        'gerentes',
                        'name',
                        fn($query) => $query
                            ->whereHas('role', fn($q) => $q->whereIn('name', [
                                'Gerente Comercial',
                                'Gerente Nacional',
                                'Administrador',
                            ]))
                    )
                    ->preload()
                    ->searchable()
                    ->dehydrated(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role.name')  // Relaciona o 'role_id' com o nome do papel
                    ->label('Função')
                    ->sortable(),

            ])
            ->filters([
                //
                SelectFilter::make('role')              // filtro de Função
                    ->label('Função')
                    ->relationship('role', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    /**
     * Filtra a query padrão do Resource.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        $role = $user->role->name;

        if ($role === 'Gerente Comercial') {
            // Só vê os vendedores subordinados
            $query->whereHas(
                'gerentes',
                fn(Builder $q) => $q->where('id', $user->id)
            );
        } elseif ($role === 'Vendedor') {
            // Vendedor só vê o próprio cadastro
            $query->where('id', $user->id);
        }
        // Admin e demais papéis continuam vendo tudo

        return $query;
    }

    public static function canCreate(): bool
    {
        return in_array(auth()->user()->role->name, [
            'Admin',
            'Gerente Nacional',
            'dev-admin',
        ]);
    }

    public static function canDelete(Model $record): bool
    {
        return in_array(auth()->user()->role->name, [
            'Admin',
            'Gerente Nacional',
        ]);
    }
}
