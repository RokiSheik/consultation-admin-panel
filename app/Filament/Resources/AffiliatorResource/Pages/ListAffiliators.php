<?php

namespace App\Filament\Resources\AffiliatorResource\Pages;

use App\Filament\Resources\AffiliatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAffiliators extends ListRecords
{
    protected static string $resource = AffiliatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
