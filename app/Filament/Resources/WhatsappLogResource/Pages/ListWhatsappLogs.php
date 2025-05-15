<?php

namespace App\Filament\Resources\WhatsappLogResource\Pages;

use App\Filament\Resources\WhatsappLogResource;
use Filament\Resources\Pages\ListRecords;

class ListWhatsappLogs extends ListRecords
{
    protected static string $resource = WhatsappLogResource::class;

    protected function getHeaderActions(): array
    {
        return []; // ✅ Hides the "Create" button from the top-right
    }
}
