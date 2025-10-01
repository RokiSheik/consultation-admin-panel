<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingPageResource\Pages;
use App\Filament\Resources\LandingPageResource\RelationManagers;
use App\Models\LandingPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class LandingPageResource extends Resource
{
    protected static ?string $model = LandingPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
        Forms\Components\TextInput::make('slug')
        ->required()
        ->unique(ignoreRecord: true)
        ->maxLength(255),

        Section::make('Section 1')->schema([
            FileUpload::make('section1_image')->label('Image')
                ->image()
                ->disk('public')
                ->imagePreviewHeight(120),
            TextInput::make('section1_video')->label('Video URL'),
            TextInput::make('section1_title')->required(),
            Repeater::make('section1_bullets')
                ->label('Bullets')
                ->schema([
                    TextInput::make('text')->label('Bullet')->required(),
                ])
                ->createItemButtonLabel('Add bullet'),
            TextInput::make('section1_regular_price')->numeric()->label('Regular price'),
            TextInput::make('section1_offer_price')->numeric()->label('Offer price'),
            TextInput::make('section1_registration_text')->label('Registration button text'),
        ])->columns(1),

        Section::make('Section 2')->schema([
            RichEditor::make('section2_description')->label('Description (HTML supported)'),
            Repeater::make('section2_class_details')
                ->label('Class details')
                ->schema([
                    TextInput::make('title')->label('Class title')->required(),
                    Forms\Components\DatePicker::make('date')->label('Date')->required(),
                    TextInput::make('start_time')->type('time')->label('Start time'),
                    TextInput::make('end_time')->type('time')->label('End time'),
                ])
                ->createItemButtonLabel('Add class'),
        ])->columns(1),

        Section::make('Section 3')->schema([
            TextInput::make('section3_submit_text')->label('Submit button text'),
        ])->columns(1),

        Section::make('Section 4')->schema([
            TextInput::make('section4_terms_title')->label('Terms & Conditions Title'),
            Repeater::make('section4_terms_bullets')
                ->schema([ TextInput::make('text')->label('Bullet') ])
                ->createItemButtonLabel('Add term bullet'),
        ])->columns(1),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\ImageColumn::make('section1_image')
                ->label('Image')
                ->circular(), // or ->square() depending on style
            Tables\Columns\TextColumn::make('section1_title')
                ->label('Title')
                ->searchable()
                ->sortable(),
            // Tables\Columns\TextColumn::make('slug')
            //     ->label('Slug')
            //     ->copyable() // nice to copy the slug
            //     ->sortable(),
            Tables\Columns\TextColumn::make('section1_offer_price')
                ->label('Offer Price')
                ->sortable(),
            // Tables\Columns\TextColumn::make('updated_at')
            //     ->label('Last Updated')
            //     ->dateTime()
            //     ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPages::route('/'),
            'create' => Pages\CreateLandingPage::route('/create'),
            'edit' => Pages\EditLandingPage::route('/{record}/edit'),
        ];
    }
}
