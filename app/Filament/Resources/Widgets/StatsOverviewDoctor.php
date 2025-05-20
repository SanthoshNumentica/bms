<?php

namespace App\Filament\Resources\Resource\Widgets;

use Filament\Widgets\ChartWidget;

class StatsOverviewDoctor extends ChartWidget
{
    protected static ?string $heading = 'Patient Count';
protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Patients',
                    'data' => [5, 10, 12, 20, 15, 8, 25], // Replace with dynamic data if needed
                    'backgroundColor' => 'rgba(56, 189, 248, 0.5)',
                    'borderColor' => 'rgba(56, 189, 248, 1)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Can be 'bar', 'pie', etc.
    }
}
