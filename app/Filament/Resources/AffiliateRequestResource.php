<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateRequestResource\Pages;
use App\Filament\Resources\AffiliateRequestResource\RelationManagers;
use App\Models\AffiliateRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliateRequestResource extends Resource
{
    protected static ?string $model = AffiliateRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Affiliate';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\TextInput::make('full_name')->required()->maxLength(255),
            Forms\Components\TextInput::make('email_address')->email()->required()->maxLength(255),
            Forms\Components\TextInput::make('phone_number')->required()->maxLength(20),
            Forms\Components\TextInput::make('whatsapp_number')->required()->maxLength(20),
            Forms\Components\TextInput::make('location')->required(),
            Forms\Components\TextInput::make('website_social_media_link')->required(),

            Forms\Components\Select::make('primary_audience')
                ->options([
                    'Business Owners' => 'Business Owners',
                    'Students' => 'Students',
                    'Freelancers' => 'Freelancers',
                    'Influencers' => 'Influencers',
                    'Coaches/Consultants' => 'Coaches/Consultants',
                    'Others (please specify)' => 'Others (please specify)',
                ])
                ->required(),

            Forms\Components\TextInput::make('followers_subscribers')->required(),
            Forms\Components\Textarea::make('promotion_platforms')->required(),
            Forms\Components\Textarea::make('reason_to_join')->required(),

            Forms\Components\Radio::make('done_affiliate_marketing')
                ->options(['Yes' => 'Yes', 'No' => 'No'])
                ->required(),

            Forms\Components\Select::make('how_heard_about_us')
                ->options([
                    'PWU Website' => 'PWU Website',
                    'Social Media' => 'Social Media',
                    'Referral' => 'Referral',
                    'PWU Student' => 'PWU Student',
                    'Other' => 'Other',
                ])
                ->required(),

            Forms\Components\Toggle::make('agreement')
                ->label('Agreed to Guidelines')
                ->disabled(),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'declined' => 'Declined',
                ])
                ->required()
                ->default('pending')
                ->native(false), // makes it look nice
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('full_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email_address')->sortable(),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'declined',
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAffiliateRequests::route('/'),
            'create' => Pages\CreateAffiliateRequest::route('/create'),
            'edit' => Pages\EditAffiliateRequest::route('/{record}/edit'),
        ];
    }
}
