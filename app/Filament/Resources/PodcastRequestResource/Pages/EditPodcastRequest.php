<?php

namespace App\Filament\Resources\PodcastRequestResource\Pages;

use App\Filament\Resources\PodcastRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPodcastRequest extends EditRecord
{
    protected static string $resource = PodcastRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
