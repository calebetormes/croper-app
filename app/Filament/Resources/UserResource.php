<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;             // lá no topo
use Filament\Tables\Filters\SelectFilter;             // lá no topo
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // rótulo no menu lateral
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pessoal';

    // rótulo no título da página (Singular / Plural)
    protected static ?string $modelLabel = 'Pessoal';

    protected static ?string $pluralModelLabel = 'Pessoal';

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
                    ->disabled(fn ($record) => isset($record) && $record->exists)  // Verifica se o registro existe antes de desabilitar
                    ->dehydrated(fn ($state) => ! empty($state))  // Só atualiza a senha se o campo não estiver vazio
                    ->maxLength(255),

                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name') // Relacionamento com o papel do usuário
                    ->required()
                    ->reactive(),

                Forms\Components\Textarea::make('observacoes')
                    ->columnSpanFull(),

                Forms\Components\MultiSelect::make('vendedores')
                    ->relationship('vendedores', 'name')
                    ->label('Vendedores')
                    ->reactive()
                    ->visible(fn ($get) => in_array($get('role_id'), [2, 3, 4]))
                    ->relationship(
                        'vendedores',
                        'name',
                        fn ($query) => $query
                            ->whereHas('role', fn ($q) => $q->where('name', 'Vendedor'))
                    )
                    ->preload()
                    ->searchable(),

                Forms\Components\MultiSelect::make('gerentes')
                    ->relationship('gerentes', 'name')
                    ->label('Gerente')
                    ->maxItems(1)
                    ->reactive()
                    ->visible(fn ($get) => in_array($get('role_id'), [1]))
                    ->relationship(
                        'gerentes',
                        'name',
                        fn ($query) => $query
                            ->whereHas('role', fn ($q) => $q->whereIn('name', [
                                'Gerente Comercial',
                                'Gerente Nacional',
                                'Administrador',
                            ]))
                    )
                    ->preload()
                    ->searchable(),
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
            $query->whereHas('gerentes', fn (Builder $q) => $q->where('id', $user->id)
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
            'Administrador',
            'Gerente Nacional',
        ]);
    }

    public static function canEdit(Model $record): bool
    {
        return in_array(auth()->user()->role->name, [
            'Administrador',
            'Gerente Nacional',
            'Gerente Comercial',
        ]);
    }

    public static function canDelete(Model $record): bool
    {
        return in_array(auth()->user()->role->name, [
            'Administrador',
            'Gerente Nacional',
        ]);
    }
}
