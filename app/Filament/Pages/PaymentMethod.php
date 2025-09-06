<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\PaymentMethodSetting;
use Filament\Notifications\Notification;

class PaymentMethod extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static string $view = 'filament.pages.payment-method';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 14;

    public ?array $paypalState = [];
    public ?array $sslcommerzState = [];

    public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}

    public function mount()
    {
        $data = PaymentMethodSetting::firstOrCreate([]);

        // Fill form states
        $this->paypalState = [
            'paypal_client_id' => $data->paypal_client_id,
            'paypal_client_secret' => $data->paypal_client_secret,
            'paypal_sandbox_mode' => $data->paypal_sandbox_mode,
        ];

        $this->sslcommerzState = [
            'sslcz_store_id' => $data->sslcz_store_id,
            'sslcz_store_password' => $data->sslcz_store_password,
            'sslcz_sandbox_mode' => $data->sslcz_sandbox_mode,
        ];
    }

    // Explicitly define forms in getForms()
    protected function getForms(): array
    {
        return [
            'paypalForm' => $this->paypalForm($this->makeForm()),
            'sslcommerzForm' => $this->sslcommerzForm($this->makeForm()),
        ];
    }

    // PayPal Form
    public function paypalForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Paypal Credential')
                ->schema([
                    TextInput::make('paypal_client_id')
                        ->label('Paypal Client ID')
                        ->required(),
                    TextInput::make('paypal_client_secret')
                        ->label('Paypal Client Secret')
                        ->required(),
                    Toggle::make('paypal_sandbox_mode')
                        ->label('Paypal Sandbox Mode'),
                ])
                ->columns(1),
        ])
        ->extraAttributes(['class' => 'max-w-3xl mx-auto'])
        ->statePath('paypalState');
    }

    // SSLCommerz Form
    public function sslcommerzForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Sslcommerz Credential')
                ->schema([
                    TextInput::make('sslcz_store_id')
                        ->label('SSLcz Store ID')
                        ->required(),
                    TextInput::make('sslcz_store_password')
                        ->label('SSLcz Store Password')
                        ->required(),
                    Toggle::make('sslcz_sandbox_mode')
                        ->label('SSLcz Sandbox Mode'),
                ])
                ->columns(1),
        ])
        ->extraAttributes(['class' => 'max-w-3xl mx-auto'])
        ->statePath('sslcommerzState');
    }

    public function savePaypal()
    {
        PaymentMethodSetting::first()->update($this->paypalState);

        Notification::make()
            ->success()
            ->title('Success')
            ->body('PayPal settings updated successfully!')
            ->send();
    }

    public function saveSslcommerz()
    {
        PaymentMethodSetting::first()->update($this->sslcommerzState);

        Notification::make()
            ->success()
            ->title('Success')
            ->body('SSLCommerz settings updated successfully!')
            ->send();
    }
}