<?php

namespace App\Filament\Resources\PodcastRequestResource\Pages;

use App\Filament\Resources\PodcastRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPodcastRequests extends ListRecords
{
    protected static string $resource = PodcastRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
