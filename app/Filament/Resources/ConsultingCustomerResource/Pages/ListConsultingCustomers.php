<?php

namespace App\Filament\Resources\ConsultingCustomerResource\Pages;

use App\Filament\Resources\ConsultingCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultingCustomers extends ListRecords
{
    protected static string $resource = ConsultingCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
