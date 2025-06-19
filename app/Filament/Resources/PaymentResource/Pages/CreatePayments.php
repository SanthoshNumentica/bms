<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayments extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),  // "Create" button
            $this->getCancelFormAction(),  // "Cancel" button
        ];
    }
}
