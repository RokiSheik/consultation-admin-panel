<?php

namespace App\Filament\Resources\PlaylistVideoResource\Pages;

use App\Filament\Resources\PlaylistVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePlaylistVideo extends CreateRecord
{
    protected static string $resource = PlaylistVideoResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
