<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingApplicationResource\Pages;
use App\Filament\Resources\TrainingApplicationResource\RelationManagers;
use App\Models\TrainingApplication;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Illuminate\Database\Eloquent\Model;



class TrainingApplicationResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Leads';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')->required(),
                Forms\Components\TextInput::make('email_address')->email()->required(),
                Forms\Components\TextInput::make('phone_number')->required(),
                Forms\Components\TextInput::make('whatsapp_number')->required(),
                Forms\Components\TextInput::make('city_country')->required(),
                Forms\Components\TextInput::make('age')->numeric()->required(),
                Forms\Components\TextInput::make('current_occupation'),
                Forms\Components\Textarea::make('current_work_description')->required(),
                Forms\Components\Textarea::make('current_stage_revenue_structure')->required(),
                Forms\Components\TextInput::make('average_monthly_income')->required(),
                Forms\Components\Select::make('invested_in_development')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('investment_details')
                    ->visible(fn ($get) => $get('invested_in_development') === 'Yes'),
                Forms\Components\TextInput::make('biggest_goal')->required(),
                Forms\Components\Textarea::make('top_challenges')->required(),
                Forms\Components\TextInput::make('program_applying_for')->required(),
                Forms\Components\Textarea::make('expected_result')->required(),
                Forms\Components\Textarea::make('why_good_fit')->required(),
                Forms\Components\TextInput::make('commitment_level')->required(),
                Forms\Components\TextInput::make('ready_to_invest')->required(),
                Forms\Components\TextInput::make('expected_budget')->required(),
                Forms\Components\TextInput::make('how_did_you_hear')->required(),
                Forms\Components\Textarea::make('additional_info'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('full_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email_address')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('city_country'),
                Tables\Columns\TextColumn::make('age'),
                Tables\Columns\TextColumn::make('program_applying_for'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->fileName('Training-requests')
                    ->defaultFormat('pdf')     // default is PDF
                    // ->disableXlsx()         // uncomment if you don’t want Excel
                    // ->disableCsv()          // uncomment if you don’t want CSV
                    ->directDownload(), 
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

        public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingApplications::route('/'),
            'create' => Pages\CreateTrainingApplication::route('/create'),
            'edit' => Pages\EditTrainingApplication::route('/{record}/edit'),
        ];
    }
}
