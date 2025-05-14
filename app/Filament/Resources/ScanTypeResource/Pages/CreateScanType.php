<?php

namespace App\Filament\Resources\ScanTypeResource\Pages;

use App\Filament\Resources\ScanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateScanType extends CreateRecord
{
    protected static string $resource = ScanTypeResource::class;
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    //      Notifications

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Scan Type has been created successfully';
    }
}
