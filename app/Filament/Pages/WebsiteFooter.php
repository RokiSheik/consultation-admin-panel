<?php

namespace App\Filament\Pages;

use App\Models\WebsiteFooterSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class WebsiteFooter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?string $navigationGroup = 'Website Setup';
    protected static ?string $navigationLabel = 'Footer';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.website-footer';
    protected static bool $shouldRegisterNavigation = false;

    public ?WebsiteFooterSetting $data = null;
    public ?array $state = null;

    public function mount()
    {
        // Retrieve or create default settings from the model
        $this->data = WebsiteFooterSetting::firstOrCreate([], [
            'footer_links' => [],
            'social_links' => [],
        ]);

        // Ensure $data is not null
        if (!$this->data) {
            throw new \Exception('Failed to initialize WebsiteFooterSetting data.');
        }

        // Store the model data in the $state property as an array
        $this->state = $this->data->toArray();

        // Fill the form with the state data
        $this->form->fill($this->state);
    }

    public function hydrate()
    {
        // Reinitialize $data if it's null
        if (!$this->data) {
            $this->data = WebsiteFooterSetting::first();
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('About')
                ->schema([
                    FileUpload::make('footer_logo')->label('Footer Logo')->image()->directory('uploads/website_setup/footer-logos')->maxSize(1024),
                    RichEditor::make('about_description')->label('About Description'),
                    TextInput::make('playstore_link')->label('Play Store Link')->url(),
                    TextInput::make('applestore_link')->label('Apple Store Link')->url(),
                    Actions::make([ 
                        Action::make('Save About')->action('saveAbout'),
                    ]), 
                ]),

            Section::make('Contact')
                ->schema([
                    RichEditor::make('office_address')->label('Office Address'),
                    TextInput::make('phone')->label('Phone'),
                    TextInput::make('email')->label('Email')->email(),
                    Actions::make([ 
                        Action::make('Save Contact')->action('saveContact'),
                    ]), 
                ]),

            Section::make('Links')
                ->schema([
                    Repeater::make('footer_links')
                        ->label('Footer Links')
                        ->schema([
                            TextInput::make('title')->label('Link Title')->required(),
                            TextInput::make('url')->label('Link URL')->url()->required(),
                        ])
                        ->columns(2)
                        ->addable(true)
                        ->deletable(true)
                        ->reorderable(true),
                    Actions::make([ 
                        Action::make('Save Links')->action('saveLinks'),
                    ]), 
                ]),

            Section::make('Footer Bottom')
                ->schema([
                    RichEditor::make('copyright_text')->label('Copyright Text'),
                    Repeater::make('social_links')
                        ->label('Social Links (Max 5)')
                        ->schema([
                            TextInput::make('platform')->label('Platform Name')->required(),
                            TextInput::make('link')->label('Platform URL')->url()->required(),
                        ])
                        ->columns(2)
                        ->maxItems(5),
                    FileUpload::make('payment_method_image')->label('Payment Method Image')->image()->directory('uploads/website_setup/payments')->maxSize(1024),
                    Actions::make([ 
                        Action::make('Save Footer Bottom')->action('saveFooterBottom'),
                    ]), 
                ]),

        ])
        ->extraAttributes(['class' => 'max-w-3xl mx-auto'])
        ->statePath('state');
    }

    public function saveAbout()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('About section updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }

    public function saveContact()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('Contact section updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }

    public function saveLinks()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('Links section updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }

    public function saveFooterBottom()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('Footer bottom section updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }
}