<?php

namespace App\Filament\Resources\ScanTypeResource\Pages;

use App\Filament\Resources\ScanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScanTypes extends ListRecords
{
    protected static string $resource = ScanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
