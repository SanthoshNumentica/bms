<?php

namespace App\Filament\Resources\CaseReportResource\Pages;

use App\Filament\Resources\CaseReportResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateCaseReport extends CreateRecord
{
    protected static string $resource = CaseReportResource::class;

    protected $casts = [
        'documents' => 'array',
    ];

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Scan Report has been created successfully';
    }

    protected function afterSave(): void
    {
        $caseReport = $this->record->load('items');

        foreach ($caseReport->items as $item) {
            Log::info('Checking item documents:', $item->documents ?? []);

            if (!empty($item->documents)) {
                $caseReport->status = 'closed';
                $caseReport->save();
                Log::info('Status changed to closed due to documents.');
                break;
            }
        }
    }

    // ✅ Hide the "Create Another" checkbox (Filament v3)
    public static function canCreateAnother(): bool
    {
        return false;
    }
}
