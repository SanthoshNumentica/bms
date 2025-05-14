<?php

namespace App\Filament\Resources\BloodGroupResource\Pages;

use App\Filament\Resources\BloodGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBloodGroup extends EditRecord
{
    protected static string $resource = BloodGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
