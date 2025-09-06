<?php

namespace App\Filament\Resources\SpeakingRequestResource\Pages;

use App\Filament\Resources\SpeakingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpeakingRequests extends ListRecords
{
    protected static string $resource = SpeakingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
