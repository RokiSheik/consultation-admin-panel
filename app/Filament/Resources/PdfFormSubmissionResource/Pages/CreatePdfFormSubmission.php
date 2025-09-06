<?php

namespace App\Filament\Resources\PdfFormSubmissionResource\Pages;

use App\Filament\Resources\PdfFormSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePdfFormSubmission extends CreateRecord
{
    protected static string $resource = PdfFormSubmissionResource::class;
}
