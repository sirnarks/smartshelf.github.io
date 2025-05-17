<?php

namespace App\Filament\Resources\GenreResource\Pages;

use App\Filament\Resources\GenreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGenres extends ListRecords
{
    protected static string $resource = GenreResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()?->role !== 'admin') {
            return []; // Hide the "New Genre" button
        }

        return [
            Actions\CreateAction::make(),
        ];
    }
}
