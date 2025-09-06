<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPackageResource\Pages;
use App\Filament\Resources\SubscriptionPackageResource\RelationManagers;
use App\Models\SubscriptionPackage;
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

class SubscriptionPackageResource extends Resource
{
    protected static ?string $model = SubscriptionPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Packages';
    protected static ?int $navigationSort = 6;
        protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('service_id')
                    ->relationship('service', 'service_name')
                    ->required()
                    ->label('Service'),

                TextInput::make('package_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('price')
                    ->numeric()
                    ->required(),

                Select::make('duration')
                    ->label('Duration')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->required(),
            ])
            ->extraAttributes(['class' => 'w-full max-w-2xl mx-auto'])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.service_name')
                    ->label('Service')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('package_name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('price')
                    ->sortable()
                    ->money('USD'),

                TextColumn::make('duration')
                    ->label('Duration')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state == 30 ? 'Monthly' : ($state == 365 ? 'Yearly' : $state)),
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
            'index' => Pages\ListSubscriptionPackages::route('/'),
            'create' => Pages\CreateSubscriptionPackage::route('/create'),
            'edit' => Pages\EditSubscriptionPackage::route('/{record}/edit'),
        ];
    }
}
