<?php

namespace App\Filament\Resources\PlaylistVideoResource\Pages;

use App\Filament\Resources\PlaylistVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlaylistVideos extends ListRecords
{
    protected static string $resource = PlaylistVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
