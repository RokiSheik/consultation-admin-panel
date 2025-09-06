<?php

namespace App\Filament\Resources\PdfFormSubmissionResource\Pages;

use App\Filament\Resources\PdfFormSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPdfFormSubmissions extends ListRecords
{
    protected static string $resource = PdfFormSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
