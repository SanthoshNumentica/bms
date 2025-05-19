<?php

namespace App\Filament\Resources\CaseReportResource\Pages;

use App\Filament\Resources\CaseReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateCaseReport extends CreateRecord
{
    protected static string $resource = CaseReportResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    //      Notifications

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Case Report  has been created successfully';
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check if any of the items has documents uploaded
        $hasDocuments = false;

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['documents'] ?? [])) {
                    $hasDocuments = true;
                    break;
                }
            }
        }

        $data['status'] = $hasDocuments ? 'closed' : 'pending';

        return $data;
    }
     protected function updateStatusBasedOnDocuments(): void
    {
        $record = $this->record->refresh(); // Reload fresh from DB

        $hasDocuments = $record->items()->whereHas('documents')->exists();

        $record->status = $hasDocuments ? 'closed' : 'pending';
        $record->save();
    }
}
