<?php

namespace App\Filament\Resources\DigitalCourseCustomerResource\Pages;

use App\Filament\Resources\DigitalCourseCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDigitalCourseCustomer extends EditRecord
{
    protected static string $resource = DigitalCourseCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
