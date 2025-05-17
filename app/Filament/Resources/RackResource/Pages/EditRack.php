<?php

namespace App\Filament\Resources\RackResource\Pages;

use App\Filament\Resources\RackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRack extends EditRecord
{
    protected static string $resource = RackResource::class;

    protected function getHeaderActions(): array
    {
        // Hide delete button for non-admins
        if (auth()->user()->role !== 'admin') {
            return [];
        }

        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        // Hide "Save Changes" and "Cancel" for non-admins
        if (auth()->user()->role !== 'admin') {
            return [];
        }

        return parent::getFormActions();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // You can optionally modify or lock fields here
        return $data;
    }

    protected function mutateFormSchema(array $schema): array
    {
        if (auth()->user()->role !== 'admin') {
            foreach ($schema as &$component) {
                if (method_exists($component, 'disabled')) {
                    $component = $component->disabled();
                }
            }
        }

        return $schema;
    }
}
