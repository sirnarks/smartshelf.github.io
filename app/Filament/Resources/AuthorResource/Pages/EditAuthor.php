<?php

namespace App\Filament\Resources\AuthorResource\Pages;

use App\Filament\Resources\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuthor extends EditRecord
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return auth()->user()?->role === 'admin'
            ? [Actions\DeleteAction::make()]
            : [];
    }

    protected function getFormActions(): array
    {
        return auth()->user()?->role === 'admin'
            ? parent::getFormActions()
            : [];
    }

    protected function canEdit(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403);
        }

        return $data;
    }
}
