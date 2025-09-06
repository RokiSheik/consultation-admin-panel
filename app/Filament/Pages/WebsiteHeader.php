<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use App\Models\WebsiteHeaderSetting;
use Filament\Notifications\Notification;

class WebsiteHeader extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?string $navigationGroup = 'Website Setup';
    protected static ?string $navigationLabel = 'Header';
    protected static ?int $navigationSort = 9;
    protected static bool $shouldRegisterNavigation = false;


    protected static string $view = 'filament.pages.website-header';

    public $header_logo; // For file path (string)
    public bool $enable_sticky_header = false;
    public array $header_menu = [];
    public ?WebsiteHeaderSetting $data = null;

    public function mount()
    {
        // Retrieve or create default settings from the model
        $this->data = WebsiteHeaderSetting::firstOrCreate([], [
            'header_logo' => null,
            'enable_sticky_header' => false,
            'header_menu' => [],
        ]);

        // Set the data to the properties
        $this->header_logo = $this->data->header_logo ?? null;
        $this->enable_sticky_header = $this->data->enable_sticky_header ?? false;
        $this->header_menu = $this->data->header_menu ?? [];

        // Pre-fill form with current data
        $this->form->fill([
            'header_logo' => $this->header_logo,
            'enable_sticky_header' => $this->enable_sticky_header,
            'header_menu' => $this->header_menu,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            // FileUpload component for single file upload
            FileUpload::make('header_logo')
                ->label('Header Logo')
                ->image() // Ensure it is an image
                ->directory('uploads/header-logos') // Upload directory
                ->maxSize(1024)  // Max file size (in KB)
                ->columnSpanFull()  // Full-width column
                ->inlineLabel()
                ->afterStateUpdated(function ($state, callable $set) {
                    // Ensure 'header_logo' is treated as a string, storing the file path
                    if (is_array($state)) {
                        $set('header_logo', $state[0] ?? null); // Get the first file's path
                    } else {
                        $set('header_logo', $state); // It is already a string
                    }
                }),

            // Sticky Header Toggle
            Toggle::make('enable_sticky_header')
                ->label('Enable Sticky Header?')
                ->default(false)
                ->inlineLabel()
                ->inline(false),

            // Header Nav Menu (Repeater)
            Repeater::make('header_menu')
                ->label('Header Nav Menu')
                ->schema([
                    TextInput::make('title')
                        ->label('Menu Title')
                        ->required()
                        ->inlineLabel()
                        ->maxLength(255),

                    TextInput::make('url')
                        ->label('Menu Link')
                        ->placeholder('Link with http:// or https://')
                        ->required()
                        ->inlineLabel()
                        ->url(),
                ])
                ->columns(2)
                ->addable(true)
                ->deletable(true)
                ->addActionLabel('Add Menu')
                ->reorderable(false),
        ])->extraAttributes(['class' => 'max-w-3xl mx-auto']);
    }

    public function save()
    {
        $data = $this->form->getState();

        // Ensure 'header_logo' is a string (not an array)
        $headerLogo = is_array($data['header_logo']) ? ($data['header_logo'][0] ?? null) : $data['header_logo'];

        // Update the settings in the database
        $this->data->update([
            'header_logo' => $headerLogo,  // Store the file path as a string
            'enable_sticky_header' => $data['enable_sticky_header'],
            'header_menu' => $data['header_menu'],
        ]);

        // Notify success using Filament's notification system
        Notification::make()
            ->success()
            ->title('Success')
            ->body('Header settings updated successfully!')
            ->send();
    }
}
