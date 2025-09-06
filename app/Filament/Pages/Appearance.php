<?php

namespace App\Filament\Pages;

use App\Models\WebsiteSetting;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Appearance extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?string $navigationLabel = 'Appearance';
    protected static ?string $navigationGroup = 'Website Setup';
    protected static ?int $navigationSort = 12;
    protected static string $view = 'filament.pages.appearance';
    public ?WebsiteSetting $data = null;
    public ?array $state = null;
    protected static bool $shouldRegisterNavigation = false;


    public function mount()
    {
        // Retrieve or create default settings from the model
        $this->data = WebsiteSetting::firstOrCreate([], [
            'meta_keywords' => [],
        ]);

        // Ensure $data is not null
        if (!$this->data) {
            throw new \Exception('Failed to initialize WebsiteSetting data.');
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
            $this->data = WebsiteSetting::first();
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            // General Settings Section
            Section::make('General')
                ->schema([
                    TextInput::make('website_name')->label('Website Name')->required(),
                    TextInput::make('site_motto')->label('Site Motto'),
                    FileUpload::make('site_icon')->label('Site Icon')->image()->directory('uploads/site-icons')->maxSize(1024),
                    ColorPicker::make('website_base_color')->label('Website Base Color'),
                    ColorPicker::make('website_base_hover_color')->label('Website Base Hover Color'),
                    Actions::make([
                        Action::make('Save General')->action('saveGeneral'),
                    ]),
                ]),

            // Global SEO Section
            Section::make('Global SEO')
                ->schema([
                    TextInput::make('meta_title')->label('Meta Title')->required(),
                    Textarea::make('meta_description')->label('Meta Description')->rows(3),
                    TagsInput::make('meta_keywords')->label('Meta Keywords')->placeholder('Comma-separated')->afterStateUpdated(fn ($state, callable $set) => $set('meta_keywords', explode(',', $state))),
                    FileUpload::make('meta_image')->label('Meta Image')->image()->directory('uploads/meta-images')->maxSize(5120),
                    Actions::make([
                        Action::make('Save SEO')->action('saveSEO'),
                    ]),
                ]),

            // Cookies Agreement Section
            Section::make('Cookies Agreement')
                ->schema([
                    Textarea::make('cookies_content')->label('Content')->rows(4),
                    Toggle::make('show_cookies_agreement')->label('Show Cookies Agreement?'),
                    Actions::make([
                        Action::make('Save Cookies')->action('saveCookies'),
                    ]),
                ]),

            // Website Popup Section
            Section::make('Website Popup')
                ->schema([
                    Toggle::make('show_website_popup')->label('Show Website Popup?'),
                    Textarea::make('popup_content')->label('Popup Content')->rows(4),
                    Actions::make([
                        Action::make('Save Popup')->action('savePopup'),
                    ]),
                ]),

            // Custom Script Section
            Section::make('Custom Script')
                ->schema([
                    Textarea::make('header_custom_script')->label('Header Custom Script')->rows(4),
                    Textarea::make('footer_custom_script')->label('Footer Custom Script')->rows(4),
                    Actions::make([
                        Action::make('Save Scripts')->action('saveScripts'),
                    ]),
                ]),
        ])
        ->extraAttributes(['class' => 'max-w-3xl mx-auto'])
        ->statePath('state');
    }

    // Save Methods for Each Section
    public function saveGeneral()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }

    public function saveSEO()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }

    public function saveCookies()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }


    public function savePopup()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('updated successfully!')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Data not found!')
                ->send();
        }
    }

    public function saveScripts()
    {
        if ($this->data) {
            $this->data->update($this->form->getState());
            Notification::make()
                ->success()
                ->title('Success')
                ->body('updated successfully!')
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
