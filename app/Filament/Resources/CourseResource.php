<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Courses";
    // protected static ?string $modelLabel = "All Customers";
    protected static ?string $navigationLabel = "All Courses";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->columnSpan(2),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                FileUpload::make('image')
                    ->image()
                    ->directory('courses') // Will be stored in storage/app/public/courses
                    ->visibility('public')
                    ->imagePreviewHeight('150')
                    ->required(),
                TextInput::make('video_url')->label('Intro Video URL'),
                TextInput::make('regular_price')->label('Regular Price')->numeric(),
                TextInput::make('price')->numeric(),
                TextInput::make('rating')->numeric(),
                TextInput::make('total_student')->numeric(),
                TextInput::make('author'),
                DatePicker::make('date'),
                TagsInput::make('tags')->placeholder('e.g. Consultation, Startup'),
                Repeater::make('content')
                    ->schema([
                        TextInput::make('value')->label('Topic'),
                    ])
                    ->label('Topics'),

                Textarea::make('details')
                    ->label('Course Description (HTML)')
                    ->rows(8),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                TextColumn::make('id')->sortable(),
                TextColumn::make('title')->limit(50)->searchable(),
                ImageColumn::make('image')->label('Thumbnail')->circular(),
                TextColumn::make('regular_price')->money('BDT'),
                TextColumn::make('price')->money('BDT'),
                TextColumn::make('rating'),
                // TextColumn::make('total_student')->label('Students'),
                TextColumn::make('author')->limit(30),
                // BadgeColumn::make('tags')
                //     ->label('Tags')
                //     ->formatStateUsing(function ($state) {
                //         if (is_array($state)) {
                //             return implode(', ', $state);
                //         }

                //         return $state; // fallback if already a string
                //     }),
                // TextColumn::make('date')->date('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('')->tooltip('View'),
                Tables\Actions\EditAction::make()->label('')->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Delete')
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
