<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;

class Login extends BaseLogin
{
    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email(),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(),

                    Checkbox::make('remember')
                        ->label('Remember me'),
                ]),
        ];
    }

    protected function getFooter(): ?string
    {
        return view('auth.login-footer')->render();
    }
}
