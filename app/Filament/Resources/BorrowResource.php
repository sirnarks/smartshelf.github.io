<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowResource\Pages;
use App\Models\Borrow;
use App\Models\Book;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class BorrowResource extends Resource
{
    protected static ?string $model = Borrow::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('reference')
                    ->default(fn () => strtoupper('BRW-' . uniqid()))
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Select::make('user_id')
                    ->label('Member')
                    ->relationship('user', 'name')
                    ->required(),

                Select::make('book_id')
                    ->label('Book')
                    ->relationship('book', 'title')
                    ->required(),

                DatePicker::make('borrowed_at')->required(),

                DatePicker::make('due_date')->required(),
                DatePicker::make('returned_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with(['book']);

                if (auth()->user()->role !== 'admin') {
                    $query->where('user_id', auth()->id());
                }
            })
            ->columns([
                TextColumn::make('book.title')
                    ->label('Book Title')
                    ->searchable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('borrowed_at')->date()->sortable(),
                TextColumn::make('due_date')->date()->sortable(),
                TextColumn::make('returned_at')
                    ->date()
                    ->sortable()
                    ->label('Returned At')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->role === 'admin'),

                Action::make('return')
                    ->label('Return')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => is_null($record->returned_at) && auth()->user()->role === 'member')
                    ->action(function ($record) {
                        $record->update(['returned_at' => now()]);

                        if ($record->book) {
                            $record->book->increment('copies');
                        }

                        Notification::make()
                            ->title('Book Returned')
                            ->body("You returned '{$record->book->title}'. Thank you!")
                            ->success()
                            ->send();
                    }),
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
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrows::route('/'),
            'create' => Pages\CreateBorrow::route('/create'),
            'edit' => Pages\EditBorrow::route('/{record}/edit'),
        ];
    }
}
