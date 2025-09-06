<?php

namespace App\Filament\Resources\PlaylistVideoResource\Pages;

use App\Filament\Resources\PlaylistVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlaylistVideo extends EditRecord
{
    protected static string $resource = PlaylistVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
