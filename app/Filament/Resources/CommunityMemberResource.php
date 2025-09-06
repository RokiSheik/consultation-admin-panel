<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunityMemberResource\Pages;
use App\Filament\Resources\CommunityMemberResource\RelationManagers;
use App\Models\CommunityMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommunityMemberResource extends Resource
{
    protected static ?string $model = CommunityMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?string $navigationGroup = "Customers";
    protected static ?string $navigationLabel = "Community Members";
    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListCommunityMembers::route('/'),
            'create' => Pages\CreateCommunityMember::route('/create'),
            'edit' => Pages\EditCommunityMember::route('/{record}/edit'),
        ];
    }
}
