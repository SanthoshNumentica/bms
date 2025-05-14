<?php

namespace App\Filament\Resources\TitleResource\Pages;

use App\Filament\Resources\TitleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTitle extends CreateRecord
{
    protected static string $resource = TitleResource::class;
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    //      Notifications

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Title has been created successfully';
    }
}
