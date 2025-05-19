<?php

namespace App\Filament\Resources\CaseReportItemResource\Pages;

use App\Filament\Resources\CaseReportItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditCaseReportItem extends EditRecord
{

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
{
    Log::info('Editing Case Report', [
        'user_id' => auth()->id(),
        'record_id' => $this->record->id,
        'new_data' => $data,
    ]);

    return $data;
}
}
