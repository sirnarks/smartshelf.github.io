<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RackResource\Pages;
use App\Filament\Resources\RackResource\RelationManagers\BooksRelationManager;
use App\Models\Rack;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RackResource extends Resource
{
    protected static ?string $model = Rack::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Rack Name')
                    ->required()
                    ->maxLength(255)
                     ->disabled(fn () => auth()->user()->role !== 'admin'),
            ]);
    }

 public static function table(Table $table): Table
{
    return $table
        ->query(
            Rack::query()
                ->orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END") // 👈 "General" first
                ->orderBy('name') // 👈 Then sort the rest alphabetically
        )
        ->columns([
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                  ->visible(fn () => auth()->user()->role === 'admin'),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make()
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
            BooksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRacks::route('/'),
            'create' => Pages\CreateRack::route('/create'),
            'edit' => Pages\EditRack::route('/{record}/edit'),
        ];
    }
}
