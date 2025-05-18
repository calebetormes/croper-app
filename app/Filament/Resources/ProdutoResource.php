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
use App\Filament\Resources\ProdutoResource\Pages\ProdutoImport;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\CreateAction;        // <— aqui
use Filament\Tables\Actions\Action;




use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

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
                ->visible(fn () => in_array(auth()->user()?->role_id, [4, 5])),
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

                TextInput::make('preco_real_rs')
                    ->label('Preço Real (R$)')
                    ->numeric()
                    ->required(),

                TextInput::make('preco_virtual_rs')
                    ->label('Preço Virtual (R$)')
                    ->numeric()
                    ->required(),

                TextInput::make('preco_real_us')
                    ->label('Preço Real (US$)')
                    ->numeric()
                    ->required(),

                TextInput::make('preco_virtual_us')
                    ->label('Preço Virtual (US$)')
                    ->numeric()
                    ->required(),

                TextInput::make('custo_rs')
                    ->label('Custo (R$)')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->headerActions([
            CreateAction::make(),
            Action::make('importarCSV')
                ->label('Importar CSV')
                ->icon('heroicon-o-cube')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('Arquivo CSV')
                        ->acceptedFileTypes(['text/csv', '.csv'])
                        ->disk('local') // armazena em storage/app
                        ->required(),
                ])
                ->action(function (array $data): void {
                    // monta o caminho completo do CSV no disco local
                    $relativePath = $data['csv_file'];
                    $fullPath = storage_path('app/' . $relativePath);

                    // importa em massa
                    Excel::import(new ProdutoImport(), $fullPath);

                    Notification::make()
                        ->success()
                        ->title('Importação concluída!')
                        ->body('Todos os produtos foram importados com sucesso.')
                        ->send();
                }),
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
