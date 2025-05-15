<?php

namespace App\Filament\Resources\StatsOverviewResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;


class StatsOverview extends ChartWidget
{
    protected static ?string $heading = ' Users';

    protected function getData(): array
    {
        $users = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('count', 'month');

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $users[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Users Count',
                    'data' => $data,
                    'backgroundColor' => 'rgba(214, 33, 84, 0.5)',
                    'borderColor' => 'rgb(246, 69, 104)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): ?array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 5, // Adjust to 1, 5, 10, etc.
                    ],
                ],
            ],
        ];
    }

    protected static ?int $sort = 1;

}

