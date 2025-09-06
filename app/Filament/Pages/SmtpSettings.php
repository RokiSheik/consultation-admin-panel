<?php

namespace App\Filament\Pages;

use App\Models\SmtpSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class SmtpSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'SMTP';
    protected static ?int $navigationSort = 13;
    protected static string $view = 'filament.pages.smtp-settings';
    

    public ?SmtpSetting $data = null;
    public ?array $state = null;

    public function mount()
    {
        $this->data = SmtpSetting::firstOrCreate([]);
        $this->state = $this->data->toArray();
        $this->form->fill($this->state);
    }

    public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('SMTP Configuration')->schema([
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'SMTP' => 'SMTP',
                        'Sendmail' => 'Sendmail',
                        'Mailgun' => 'Mailgun',
                    ])
                    ->required(),

                TextInput::make('mail_host')->label('Mail Host')->required(),
                TextInput::make('mail_port')->label('Mail Port')->numeric()->required(),
                TextInput::make('mail_username')->label('Mail Username')->required(),
                TextInput::make('mail_password')->label('Mail Password')
                    ->password()
                    ->required(),
                TextInput::make('mail_encryption')->label('Mail Encryption')->required(),
                TextInput::make('mail_from_address')->label('Mail From Address')->email()->required(),
                TextInput::make('mail_from_name')->label('Mail From Name')->required(),
            ]),
        ])
        ->extraAttributes(['class' => 'max-w-3xl mx-auto'])
        ->statePath('state');
    }

    public function saveSettings()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('SMTP settings updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Failed to update settings!')
                ->send();
        }
    }
}
