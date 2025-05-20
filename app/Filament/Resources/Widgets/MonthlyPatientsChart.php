<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CaseReport;
use App\Models\WhatsappLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyPatientsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Patients';

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(null, $month, 1)->format('F');
        });

        $patients = Patient::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $data = $months->mapWithKeys(function ($monthName, $index) use ($patients) {
            return [$monthName => $patients->get($index + 1, 0)];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Patients',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected static ?int $sort = 2;
}

class MonthlyDoctorsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Doctors';

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(null, $month, 1)->format('F');
        });

        $doctors = Doctor::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $data = $months->mapWithKeys(function ($monthName, $index) use ($doctors) {
            return [$monthName => $doctors->get($index + 1, 0)];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Doctors',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#10b981', // green color for doctors
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected static ?int $sort = 3;
}
class MonthlyCaseReportsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Case Reports';

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(null, $month, 1)->format('F');
        });

        $caseReports = CaseReport::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $data = $months->mapWithKeys(function ($monthName, $index) use ($caseReports) {
            return [$monthName => $caseReports->get($index + 1, 0)];
        });

        return [
            'datasets' => [
                [
                    'label' => 'CaseReport',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#10b981', // green color for CaseReport
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected static ?int $sort = 3;
}
class MonthlyWhatsapplogChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly WhatsappLog';

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(null, $month, 1)->format('F');
        });

        $whatsappLog = WhatsappLog::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $data = $months->mapWithKeys(function ($monthName, $index) use ($whatsappLog) {
            return [$monthName => $whatsappLog->get($index + 1, 0)];
        });

        return [
            'datasets' => [
                [
                    'label' => 'WhatsappLog',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#10b981', // green color for WhatsappLog
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected static ?int $sort = 3;
}
