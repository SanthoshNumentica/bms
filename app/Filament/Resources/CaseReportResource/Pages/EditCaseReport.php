<?php

namespace App\Filament\Resources\CaseReportResource\Pages;

use App\Filament\Resources\CaseReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EditCaseReport extends EditRecord
{
    protected static string $resource = CaseReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

     protected function mutateFormDataBeforeSave(array $data): array
    {
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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Case Report has been updated successfully';
    }

}




