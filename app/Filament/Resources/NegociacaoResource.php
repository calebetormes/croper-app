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
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NegociacaoResource extends Resource
{
    protected static ?string $model = Negociacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ProdutosSectionForm::make(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('data_negocio')->date()->sortable(),
                Tables\Columns\TextColumn::make('gerente.name')->label('Gerente')->sortable(),
                Tables\Columns\TextColumn::make('vendedor.name')->label('RTV')->sortable(),
                Tables\Columns\TextColumn::make('cliente')->searchable(),
                Tables\Columns\TextColumn::make('cultura.nome')->label('Cultura')->sortable(),
                Tables\Columns\TextColumn::make('status_negociacao.nome')->label('Status')->sortable(),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
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
}
