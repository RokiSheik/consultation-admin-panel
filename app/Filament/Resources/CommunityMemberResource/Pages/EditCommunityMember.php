<?php

namespace App\Filament\Resources\CommunityMemberResource\Pages;

use App\Filament\Resources\CommunityMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommunityMember extends EditRecord
{
    protected static string $resource = CommunityMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
