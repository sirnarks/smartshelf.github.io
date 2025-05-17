<?php

namespace App\Filament\Resources\BorrowResource\Pages;

use App\Filament\Resources\BorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBorrows extends ListRecords
{
    protected static string $resource = BorrowResource::class;

    protected function canCreate(): bool
    {
        // Only admin can create borrows
        return auth()->user()->role === 'admin';
    }

    protected function getHeaderActions(): array
    {
        // Hide "New Borrow" button if not admin
        return auth()->user()->role === 'admin' ? [
            Actions\CreateAction::make(),
        ] : [];
    }
}
