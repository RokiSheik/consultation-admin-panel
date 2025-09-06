<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultingRequestResource\Pages;
use App\Models\ConsultingRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class ConsultingRequestResource extends Resource
{
    protected static ?string $model = ConsultingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'Leads';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')->required(),
                Forms\Components\TextInput::make('email_address')->email()->required(),
                Forms\Components\TextInput::make('phone_number'),
                Forms\Components\TextInput::make('whatsapp_number'),
                Forms\Components\TextInput::make('company_name'),
                Forms\Components\TextInput::make('website_social_media'),
                Forms\Components\TextInput::make('city_country'),
                Forms\Components\TextInput::make('age'),

                Forms\Components\Textarea::make('how_heard_about_us'),
                Forms\Components\TextInput::make('how_heard_about_us_other'),

                Forms\Components\Textarea::make('applying_for'),
                Forms\Components\TextInput::make('applying_for_other'),

                Forms\Components\Textarea::make('best_describes_you'),
                Forms\Components\TextInput::make('best_describes_you_other'),

                Forms\Components\Textarea::make('current_business_description'),
                Forms\Components\TextInput::make('current_monthly_revenue'),
                Forms\Components\TextInput::make('business_duration'),

                Forms\Components\Textarea::make('priority_next_months'),
                Forms\Components\TextInput::make('priority_next_months_other'),

                Forms\Components\Textarea::make('biggest_challenges'),
                Forms\Components\TextInput::make('biggest_challenges_other'),

                Forms\Components\Textarea::make('stopped_fixing_challenges'),
                Forms\Components\Textarea::make('specific_outcome'),
                Forms\Components\TextInput::make('specific_outcome_other'),

                Forms\Components\TextInput::make('commitment_level'),
                Forms\Components\TextInput::make('willing_to_invest'),
                Forms\Components\TextInput::make('investment_budget'),

                Forms\Components\TextInput::make('book_discovery_call'),
                Forms\Components\Textarea::make('specific_request'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('full_name')->searchable(),
                Tables\Columns\TextColumn::make('email_address')->searchable(),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('whatsapp_number'),
                Tables\Columns\TextColumn::make('city_country'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->fileName('consulting-requests')
                    ->defaultFormat('pdf')     // default is PDF
                    // ->disableXlsx()         // uncomment if you don’t want Excel
                    // ->disableCsv()          // uncomment if you don’t want CSV
                    ->directDownload(),        // skip modal, download directly
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
            'index' => Pages\ListConsultingRequests::route('/'),
            // You can remove create if not needed
            'create' => Pages\CreateConsultingRequest::route('/create'),
            'edit' => Pages\EditConsultingRequest::route('/{record}/edit'),
        ];
    }
}
