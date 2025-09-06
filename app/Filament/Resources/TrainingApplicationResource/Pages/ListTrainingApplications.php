<?php

namespace App\Filament\Resources\TrainingApplicationResource\Pages;

use App\Filament\Resources\TrainingApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingApplications extends ListRecords
{
    protected static string $resource = TrainingApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
