<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ServicePerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Service Revenue Overview';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $minHeight = '350px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $serviceNames = [
            'Course', 'Content', 'Consulting', 'Community',
            'Speaking', 'Training', 'Audit', 'Podcast',
        ];

        $revenueData = Order::select('service_name', DB::raw('SUM(total_amount) as total_revenue'))
        ->where('status', 'completed') // ✅ Only completed orders
        ->groupBy('service_name')
        ->pluck('total_revenue', 'service_name')
        ->toArray();

        $labels = [];
        $revenues = [];

        foreach ($serviceNames as $service) {
            $labels[] = $service;
            $revenues[] = $revenueData[$service] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Revenue',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.7)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue ($)',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
        ];
    }
}
