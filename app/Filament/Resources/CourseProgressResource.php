<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseProgressResource\Pages;
use App\Filament\Resources\CourseProgressResource\RelationManagers;
use App\Models\CourseProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\PlaylistVideo;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;


class CourseProgressResource extends Resource
{
    protected static ?string $model = CourseProgress::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Courses';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('customer_id')
                ->label('Customer')
                ->disabled()
                ->afterStateHydrated(function ($component, $state, $record) {
                    $component->state($record->customer?->name ?? '');
                }),

            Forms\Components\TextInput::make('course_id')
                ->label('Course')
                ->disabled()
                ->afterStateHydrated(function ($component, $state, $record) {
                    $component->state($record->course?->title ?? '');
                }),

            KeyValue::make('completed_videos')
    ->label('Course Progress (Videos)')
    ->keyLabel('Video Name')
    ->valueLabel('Status')
    ->afterStateHydrated(function ($component, $state, $record) {
        if (! $record) {
            $component->state([]);
            return;
        }

        // Completed IDs as array of strings
        $completedIds = $record->completed_videos ?? [];
        if (! is_array($completedIds)) {
            $completedIds = json_decode($completedIds, true) ?: [];
        }
        $completedIds = array_map('strval', $completedIds); // normalize to strings

        $videos = $record->course?->playlistVideos ?? collect();

        $data = [];
        foreach ($videos as $video) {
            $videoId = (string) $video->order;

            $status = in_array($videoId, $completedIds, true)
                ? "✅ Completed"
                : "❌ Not Completed";

            $data[$video->title] = $status;
        }

        $component->state($data);
    })
    ->dehydrated(false)
    ->disabled()
    ->columnSpanFull(),

        ]);
}




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('course.title')->label('Course')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('completed_videos')
    ->label('Course Progress')
    ->getStateUsing(function ($record) {
        $totalVideos = $record->course?->playlistVideos()->count() ?? 0;
        $completedVideos = count($record->completed_videos ?? []);

        if ($totalVideos === 0) {
            return 'No videos in course';
        }

        $percentage = round(($completedVideos / $totalVideos) * 100, 2);

        return "{$totalVideos}-{$completedVideos} videos completed ({$percentage}%)";
    })
    ->sortable(),



                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y h:i A'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCourseProgress::route('/'),
            'create' => Pages\CreateCourseProgress::route('/create'),
            'edit' => Pages\EditCourseProgress::route('/{record}/edit'),
        ];
    }
}
