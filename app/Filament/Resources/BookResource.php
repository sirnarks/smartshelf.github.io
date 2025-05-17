<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use App\Models\Borrow;
use Filament\Forms;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required()->maxLength(255),

            Select::make('authors')
                ->label('Authors')
                ->multiple()
                ->relationship('authors', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Author Name')
                        ->required()
                        ->maxLength(255)
                        ->rules([Rule::unique('authors', 'name')]),
                ]),

            Select::make('genres')
                ->label('Genres')
                ->multiple()
                ->relationship('genres', 'name')
                ->preload()
                ->searchable()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Genre Name')
                        ->required()
                        ->maxLength(255)
                        ->rules([Rule::unique('genres', 'name')]),
                ]),

            Select::make('rack_id')
                ->label('Rack')
                ->relationship('rack', 'name', fn($query) => $query->orderBy('name'))
                ->preload(),

            TextInput::make('copies')->required()->numeric()->default(1),
            TextInput::make('isbn')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Book::query()->orderBy('title'))
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('authors.name')->label('Authors')->badge()->separator(', '),
                TextColumn::make('genres.name')->label('Genres')->badge()->separator(', '),
                TextColumn::make('copies')
                    ->label('Availability')
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state > 0 ? "$state available" : 'Unavailable'),
                TextColumn::make('isbn')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(),
            ])
            ->filters([SelectFilter::make('genres')
        ->label('Genre')
        ->multiple()
        ->relationship('genre', 'name'),])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn() => auth()->user()->role === 'admin'),
                Tables\Actions\DeleteAction::make()->visible(fn() => auth()->user()->role === 'admin'),

                Action::make('borrow')
              ->action(function (Book $record) {
    $user = auth()->user();

    // Check if user has an active borrow
    $hasActiveBorrow = Borrow::where('user_id', $user->id)
        ->whereNull('returned_at')
        ->exists();

    if ($hasActiveBorrow) {
        Notification::make()
            ->title('You already borrowed a book')
            ->body('Return your current book before borrowing another.')
            ->danger()
            ->send();

        return;
    }

    $reference = strtoupper('BRW-' . uniqid());

    Borrow::create([
        'user_id'     => $user->id,
        'book_id'     => $record->id,
        'borrowed_at' => now(),
        'due_date'    => now()->addDays(3),
        'reference'   => $reference,
    ]);

    $record->decrement('copies');

    Notification::make()
        ->title('Book Borrowed')
        ->body("You borrowed '{$record->title}'. Ref: {$reference}. Return by " . now()->addDays(3)->toFormattedDateString())
        ->success()
        ->send();
})

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(fn() => auth()->user()->role === 'admin'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AuthorsRelationManager::class,
            RelationManagers\GenresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->role === 'admin';
    }
}
