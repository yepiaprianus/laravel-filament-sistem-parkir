<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

class Login extends BaseLogin
{

    public function form(Schema $form): Schema
    {
        return $form->schema([
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->autofocus(),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }
    // protected string $view = 'filament.pages.login';
}
