<?php

namespace App\Filament\Resources\ConsultingCustomerResource\Pages;

use App\Filament\Resources\ConsultingCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsultingCustomer extends EditRecord
{
    protected static string $resource = ConsultingCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
