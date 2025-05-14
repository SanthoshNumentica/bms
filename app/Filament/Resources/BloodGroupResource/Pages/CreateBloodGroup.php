<?php

namespace App\Filament\Resources\BloodGroupResource\Pages;

use App\Filament\Resources\BloodGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBloodGroup extends CreateRecord
{
    protected static string $resource = BloodGroupResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    //      Notifications

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Blood Group has been created successfully';
    }
}
