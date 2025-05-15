<?php

namespace App\Filament\Resources\WhatsappLogResource\Pages;

use App\Filament\Resources\WhatsappLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsappLog extends EditRecord
{
    protected static string $resource = WhatsappLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
