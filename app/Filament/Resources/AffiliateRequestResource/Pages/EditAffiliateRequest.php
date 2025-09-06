<?php

namespace App\Filament\Resources\AffiliateRequestResource\Pages;

use App\Filament\Resources\AffiliateRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliateRequest extends EditRecord
{
    protected static string $resource = AffiliateRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
