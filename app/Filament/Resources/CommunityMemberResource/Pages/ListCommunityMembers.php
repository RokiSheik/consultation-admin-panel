<?php

namespace App\Filament\Resources\CommunityMemberResource\Pages;

use App\Filament\Resources\CommunityMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommunityMembers extends ListRecords
{
    protected static string $resource = CommunityMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
