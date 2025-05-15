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
        return 'Patient has been created successfully';
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
                if ($documents && count($documents) > 0) {
                    $response = Http::post(url("/whatsAppSend"), $data);
                    Log::info('WhatsApp API Request Data:', $data);
                    Log::info('WhatsApp API Response:', ['response' => $response->json()]);
                } else {
                    Log::warning('Patient not found for report ID: ' . $record->id);
                }
            }
        }
    }
}
