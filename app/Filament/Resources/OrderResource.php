<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    // ->disabled()
                    ->required(),

                TextInput::make('total_amount')
                    ->numeric()
                    // ->disabled()
                    ->required(),

                TextInput::make('service_name') // Change from 'service' to 'services'
                    ->label('Service')
                    // ->disabled(),
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

                TextInput::make('package_type') // Dropdown for Package Selection
                    ->required()
                    // ->disabled()
                    ->label('Package'),

                TextInput::make('order_id') // Display the Order ID
                    // ->disabled()
                    ->default(fn($record) => $record->order_id ?? 'N/A')
                    ->label('Order ID'),

                TextInput::make('order_date')
    ->disabled()
    ->default(fn($record) => $record?->order_date 
        ? $record->order_date->format('Y-m-d H:i:s') 
        : 'N/A')
    ->label('Order Date'),


            ])
            ->extraAttributes(['class' => 'w-full max-w-2xl mx-auto'])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                TextColumn::make('order_id'),
                TextColumn::make('customer_name')
                ->label('Customer')
                ->getStateUsing(function ($record) {
                    if ($record->customer) {
                        // If the customer exists, return the name
                        return $record->customer->name;
                    } else {
                        // Calculate lead id from pseudo-customer id
                        $leadId = $record->customer_id - 10000;
                        $lead = \App\Models\Lead::find($leadId);
                        return $lead?->name ?? 'N/A';
                    }
                }),
                TextColumn::make('service_name')->label('Service'),
                TextColumn::make('package_type')->label('Package'),

                TextColumn::make('total_amount')->label('Total Amount'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                TextColumn::make('order_date')->sortable()->dateTime(),
            ])->defaultSort('order_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->tooltip('Edit')
                    ->url(fn($record) => route('filament.admin.resources.orders.edit', ['record' => $record->getKey()])),

            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->fileName('Order-list')
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
    public static function canCreate(): bool
    {
        return true; // This will remove the "Create New" button
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
