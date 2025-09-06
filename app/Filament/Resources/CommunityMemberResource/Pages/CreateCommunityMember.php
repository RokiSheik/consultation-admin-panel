<?php

namespace App\Filament\Resources\CommunityMemberResource\Pages;

use App\Filament\Resources\CommunityMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCommunityMember extends CreateRecord
{
    protected static string $resource = CommunityMemberResource::class;
}
