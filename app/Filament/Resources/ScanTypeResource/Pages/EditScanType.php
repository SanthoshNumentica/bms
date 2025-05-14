<?php

namespace App\Filament\Resources\ScanTypeResource\Pages;

use App\Filament\Resources\ScanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScanType extends EditRecord
{
    protected static string $resource = ScanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
