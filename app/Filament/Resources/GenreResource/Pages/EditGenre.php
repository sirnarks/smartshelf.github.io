<?php

namespace App\Filament\Resources\GenreResource\Pages;

use App\Filament\Resources\GenreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;

class EditGenre extends EditRecord
{
    protected static string $resource = GenreResource::class;

    

    protected function getHeaderActions(): array
    {
        // Show Delete button only for admins
        return auth()->user()?->role === 'admin'
            ? [Actions\DeleteAction::make()]
            : [];
    }

    protected function getFormActions(): array
    {
        // Show Save/Cancel only for admins
        return auth()->user()?->role === 'admin'
            ? parent::getFormActions()
            : [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Disable the name field for non-admins
        if (auth()->user()?->role !== 'admin') {
            $this->form->getComponent('name')?->disabled();
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Prevent saving by non-admins
        if (auth()->user()?->role !== 'admin') {
            abort(403);
        }

        return $data;
    }

    protected function canEdit(): bool
    {
        // Prevent edit access entirely if needed
        return true; // Keep as true so non-admins can still view the page
    }
}
