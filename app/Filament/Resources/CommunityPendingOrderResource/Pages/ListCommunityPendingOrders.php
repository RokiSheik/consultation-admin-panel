<?php

namespace App\Filament\Resources\CommunityPendingOrderResource\Pages;

use App\Filament\Resources\CommunityPendingOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommunityPendingOrders extends ListRecords
{
    protected static string $resource = CommunityPendingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
