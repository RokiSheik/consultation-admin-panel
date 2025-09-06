<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunityPendingOrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Notifications\OrderSuccessNotification;


class CommunityPendingOrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Community Pending Payment';
    protected static ?string $navigationGroup = 'Invoice';

    protected static ?int $navigationSort = 3;


    // 🔹 Form for editing
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('order_id')->label('Order ID')->disabled(),
                TextInput::make('customer_name')
    ->label('Customer')
    ->disabled()
    ->afterStateHydrated(function (TextInput $component, $state, $record) {
        $component->state($record->customer->name ?? '');
    }),

TextInput::make('customer_email')
    ->label('Customer Email')
    ->disabled()
    ->afterStateHydrated(function (TextInput $component, $state, $record) {
        $component->state($record->customer->email ?? '');
    }),

                TextInput::make('service_name')->label('Service')->disabled(),
                TextInput::make('package_type')->label('Package')->disabled(),
                TextInput::make('total_amount')->label('Total Amount')->disabled(),
                Select::make('status') // ✅ Status as dropdown in form
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                TextInput::make('order_date')->label('Order Date')->disabled(),
            ]);
    }

    // 🔹 Table for listing
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                TextColumn::make('order_id')->label('Order ID'),
                TextColumn::make('customer.name')->label('Customer'),
                TextColumn::make('customer.email')->label('Customer Email'),
                TextColumn::make('service_name')->label('Service'),
                TextColumn::make('package_type')->label('Package'),
                TextColumn::make('total_amount')->label('Total Amount'),
                TextColumn::make('status') // ✅ Keep table column as TextColumn
                    ->badge(fn($state) => match($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                TextColumn::make('order_date')->label('Order Date')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('order_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommunityPendingOrders::route('/'),
            'edit' => Pages\EditCommunityPendingOrder::route('/{record}/edit'),
        ];
    }

    // 🔹 Only show pending orders with total_amount > 300000
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'pending')
            ->where('total_amount', '>', 300000);
    }
}
