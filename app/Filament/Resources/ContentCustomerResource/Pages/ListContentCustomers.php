<?php

namespace App\Filament\Resources\ContentCustomerResource\Pages;

use App\Filament\Resources\ContentCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContentCustomers extends ListRecords
{
    protected static string $resource = ContentCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
