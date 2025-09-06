<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;


class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?string $navigationGroup = 'Website Setup';
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationLabel = 'Pages';
        protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Page Content')
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))->debounce(2000),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Page::class, 'slug'),

                    RichEditor::make('content')
                        ->label('Content')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('SEO Settings')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->maxLength(255),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->rows(3),

                    TagsInput::make('meta_keywords')
                        ->label('Meta Keywords')
                        ->placeholder('Enter keywords separated by commas'),

                    FileUpload::make('meta_image')
                        ->label('Meta Image')
                        ->image()
                        ->disk('public')
                        ->directory('uploads/pages/meta-images')
                        ->maxSize(5120), // 5MB max
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                ImageColumn::make('meta_image')->label('Image')->disk('public'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Delete'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
