<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                ->required()
                ->unique('coupons', 'code')
                ->maxLength(20)
                ->columnSpan(2),

            Forms\Components\Select::make('applies_to')
                ->options([
                    'Courses' => 'Digital Courses',
                    'Community' => 'Community Membership',
                    'Consulting' => 'Consulting Service',
                    'Content' => 'Content Subscription',
                    'Training' => 'Training',
                    'Speaking' => 'Speaking',
                    'Podcast' => 'Podcast'
                ])
                ->multiple()
                ->required(),

            Forms\Components\TextInput::make('discount_amount')
                ->numeric()
                ->required()
                ->prefix('$'),

            Forms\Components\Select::make('discount_type')
                ->options(['percentage' => 'Percentage', 'fixed' => 'Fixed'])
                ->required(),

            Forms\Components\DatePicker::make('expires_at')
                ->label('Expiration Date')
                ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('discount_amount')->sortable(),
                Tables\Columns\TextColumn::make('discount_type')->sortable(),
                Tables\Columns\TextColumn::make('applies_to')->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
                Tables\Columns\TextColumn::make('expires_at')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
