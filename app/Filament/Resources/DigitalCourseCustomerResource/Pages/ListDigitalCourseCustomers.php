<?php

namespace App\Filament\Resources\DigitalCourseCustomerResource\Pages;

use App\Filament\Resources\DigitalCourseCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDigitalCourseCustomers extends ListRecords
{
    protected static string $resource = DigitalCourseCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
