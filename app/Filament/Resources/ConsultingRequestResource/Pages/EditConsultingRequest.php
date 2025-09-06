<?php

namespace App\Filament\Resources\ConsultingRequestResource\Pages;

use App\Filament\Resources\ConsultingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsultingRequest extends EditRecord
{
    protected static string $resource = ConsultingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
