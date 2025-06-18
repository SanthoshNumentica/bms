<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\WhatsappLog;
use App\Models\CaseReport;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;


class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getCards(): array
    {

        return [
            Card::make('Total Patients', Patient::count()),
            Card::make('Total Doctors', Doctor::count()),
            Card::make('Total WhatsApp Sent', WhatsappLog::count()),
        ];
    }
}
