<?php

namespace App\Filament\Resources\CaseReportResource\Pages;

use App\Filament\Resources\CaseReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCaseReport extends EditRecord
{
    protected static string $resource = CaseReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->load('items');
        return $data;
    }

   protected function mutateFormDataBeforeSave(array $data): array
{
    $hasDocuments = $this->record->items->some(function ($item) {
        return $item->documents && collect($item->documents)->isNotEmpty();
    });

    $data['status'] = $hasDocuments ? 'closed' : 'pending';

    if ($hasDocuments) {
        $whatsappController = app(\App\Http\Controllers\WhatsAppController::class);
        $whatsappController->findCaseReportById($this->record->id);
    }

    return $data;
}



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Case Report has been updated successfully';
    }
}
