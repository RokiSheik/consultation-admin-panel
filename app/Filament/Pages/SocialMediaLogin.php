<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\SocialLoginSetting;
use Filament\Notifications\Notification;

class SocialMediaLogin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 15;
    
    protected static string $view = 'filament.pages.social-media-login';

    public ?array $facebookState = [];
    public ?array $googleState = [];
    public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}

    public function mount()
    {
        $data = SocialLoginSetting::firstOrCreate([]);

        // Fill form states
        $this->facebookState = [
            'facebook_app_id' => $data->facebook_app_id,
            'facebook_app_secret' => $data->facebook_app_secret,

        ];

        $this->googleState = [
            'google_client_id' => $data->google_client_id,
            'google_client_secret' => $data->google_client_secret,
        ];
    }

    // Explicitly define forms in getForms()
    protected function getForms(): array
    {
        return [
            'facebookForm' => $this->facebookForm($this->makeForm()),
            'googleForm' => $this->googleForm($this->makeForm()),
        ];
    }

    // Facebook Form
    public function facebookForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Facebook Credential')
                ->schema([
                    TextInput::make('facebook_app_id')
                        ->label('APP ID')
                        ->required(),
                    TextInput::make('facebook_app_secret')
                        ->label('APP Secret')
                        ->required(),
                ])
                ->columns(1),
        ])
        ->statePath('facebookState');
    }

    // Google Form
    public function googleForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Google Credential')
                ->schema([
                    TextInput::make('google_client_id')
                        ->label('CLIENT ID')
                        ->required(),
                    TextInput::make('google_client_secret')
                        ->label('Client Secret')
                        ->required(),
                ])
                ->columns(1),
        ])
        ->statePath('googleState');
    }

    public function saveFacebook()
    {
        SocialLoginSetting::first()->update($this->facebookState);

        Notification::make()
            ->success()
            ->title('Success')
            ->body('updated successfully!')
            ->send();
    }

    public function saveGoogle()
    {
        SocialLoginSetting::first()->update($this->googleState);

        Notification::make()
            ->success()
            ->title('Success')
            ->body('updated successfully!')
            ->send();
    }
}
