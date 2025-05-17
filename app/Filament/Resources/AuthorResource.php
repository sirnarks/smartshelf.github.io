<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Filament\Resources\AuthorResource\RelationManagers;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->disabled(fn () => auth()->user()?->role !== 'admin'),
            ]);
    }
    public static function canCreate(): bool
{
    return auth()->user()?->role === 'admin';
}


    public static function table(Table $table): Table
    {
           return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
       Tables\Columns\TextColumn::make('books_count')
                ->label('Books')
                ->counts('books') // 👈 Automatically counts the relationship
                ->sortable(),
            ])
        ->filters([
            //
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->visible(fn () => auth()->user()->role === 'admin'),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->visible(fn () => auth()->user()->role === 'admin'),
            Tables\Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()->role === 'admin'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()->role === 'admin'),
            ]),
        ]);
}

    public static function getRelations(): array
    {
        return [
            RelationManagers\BooksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
