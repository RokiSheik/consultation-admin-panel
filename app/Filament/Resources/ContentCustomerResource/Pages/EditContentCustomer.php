<?php

namespace App\Filament\Resources\ContentCustomerResource\Pages;

use App\Filament\Resources\ContentCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentCustomer extends EditRecord
{
    protected static string $resource = ContentCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
