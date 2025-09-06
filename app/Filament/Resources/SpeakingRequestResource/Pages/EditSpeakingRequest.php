<?php

namespace App\Filament\Resources\SpeakingRequestResource\Pages;

use App\Filament\Resources\SpeakingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpeakingRequest extends EditRecord
{
    protected static string $resource = SpeakingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
