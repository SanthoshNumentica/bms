<?php

namespace App\Filament\Resources\CaseReportItemResource\Pages;

use App\Filament\Resources\CaseReportItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaseReportItems extends ListRecords
{
    protected static string $resource = CaseReportItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
