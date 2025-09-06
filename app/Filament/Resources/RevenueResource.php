<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RevenueResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RevenueResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->label('Order ID')
                    ->disabled(),

                Forms\Components\TextInput::make('customer_id')
                    ->label('Customer ID')
                    ->disabled(),

                Forms\Components\TextInput::make('service_name')
                    ->label('Service')
                    ->disabled(),

                Forms\Components\TextInput::make('package_type')
                    ->label('Package')
                    ->disabled(),

                Forms\Components\TextInput::make('total_amount')
                    ->label('Amount')
                    ->prefix('$')
                    ->disabled(),

                Forms\Components\DatePicker::make('order_date')
                    ->label('Order Date')
                    ->disabled(),

                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                TextColumn::make('order_id')->label('Order ID')->sortable(),
                TextColumn::make('service_name')->label('Service'),
                TextColumn::make('package_type')->label('Package'),
                TextColumn::make('total_amount')->label('Amount')->money('usd', true),
                TextColumn::make('order_date')->label('Order Date')->date(),
                TextColumn::make('status')->label('Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
        public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRevenues::route('/'),
            'create' => Pages\CreateRevenue::route('/create'),
            'edit' => Pages\EditRevenue::route('/{record}/edit'),
        ];
    }
}
