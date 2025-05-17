<?php

namespace App\Filament\Resources\GenreResource\Pages;

use App\Filament\Resources\GenreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGenre extends CreateRecord
{
    protected static string $resource = GenreResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->role === 'admin';
    }
}
