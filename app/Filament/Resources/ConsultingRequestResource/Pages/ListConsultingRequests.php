<?php

namespace App\Filament\Resources\ConsultingRequestResource\Pages;

use App\Filament\Resources\ConsultingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultingRequests extends ListRecords
{
    protected static string $resource = ConsultingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
