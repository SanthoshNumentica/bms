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
        // Automatically set status to 'closed' if documents are uploaded
        if (!empty($data['documents']) && is_array($data['documents'])) {
            $data['status'] = 'closed';
        } else {
            $data['status'] = 'pending';
        }

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Case Report has been updated successfully';
    }
    protected function saved(): void
{
    parent::saved();

    $record = $this->record;
    $documents = $record->documents ?? [];

    if (is_array($documents) && count($documents) > 0) {
        $patient = $record->patient;

        if ($patient) {
            $data = [
                'patient_name' => $patient->name ?? '',
                'patient_phone' => $patient->phone ?? '',
                'report_id' => $record->id,
                'report_date' => $record->created_at,
                'documents' => $documents,
            ];

            $response = Http::post(url("/whatsAppSend"), $data);

            Log::info('WhatsApp API Request Data:', $data);
            Log::info('WhatsApp API Response:', ['response' => $response->json()]);
        } else {
            Log::warning('Patient not found for report ID: ' . $record->id);
        }
    }
}



}
