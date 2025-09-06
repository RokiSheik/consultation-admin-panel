<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliatorResource\Pages;
use App\Filament\Resources\AffiliatorResource\RelationManagers;
use App\Models\Affiliator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliatorResource extends Resource
{
    protected static ?string $model = Affiliator::class;
    protected static ?string $navigationGroup = 'Affiliate';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function shouldRegisterNavigation(): bool
{
    $user = auth()->user();
    return $user && ($user->isAdmin() || $user->isDeveloperRole());
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('balance')->disabled(), // readonly
                Forms\Components\TextInput::make('payment_method')->required(),
                Forms\Components\TextInput::make('payment_method_details')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('balance')->formatStateUsing(fn($state) => "৳ " . number_format($state, 2)),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_method_details'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAffiliators::route('/'),
            'create' => Pages\CreateAffiliator::route('/create'),
            'edit' => Pages\EditAffiliator::route('/{record}/edit'),
        ];
    }
}
