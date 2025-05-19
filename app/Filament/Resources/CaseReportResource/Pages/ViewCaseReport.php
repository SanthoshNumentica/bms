<?php

namespace App\Filament\Resources\CaseReportResource\Pages;

use App\Filament\Resources\CaseReportResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCaseReport extends ViewRecord
{
    protected static string $resource = CaseReportResource::class;

    // Optional: You can define custom actions shown on the view page
    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
