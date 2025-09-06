<?php

namespace App\Filament\Widgets;

use App\Models\Order; // Make sure this path is correct for your Order model
use Carbon\Carbon;    // Import Carbon for date manipulation
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB; // For raw database queries (e.g., HOUR(), DATE(), MONTH())
use Illuminate\Support\Collection; // Import Collection for proper usage

class CompletedOrdersChart extends ChartWidget
{
    // Livewire property to hold the selected filter period.
    // It's nullable and initialized in the mount method.
    public ?string $filter; 

    protected static ?string $heading = 'Completed Orders Trend';

    // Initialize the filter property when the Livewire component mounts
    public function mount(): void
    {
        $this->filter = 'this_month';
    }

    // The type of chart to display
    protected function getType(): string
    {
        return 'line';
    }

    // Define the filter options that will appear above the chart
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'this_week' => 'This Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
        ];
    }

    protected function getData(): array
    {
        $startDate = null;
        $endDate = Carbon::now(); // Default end date to now
        $labels = []; // Labels for the chart's X-axis
        $dataCounts = []; // Data points for the chart's Y-axis

        // Determine start and end dates based on the selected filter
        switch ($this->filter) {
            case 'today':
                $startDate = Carbon::today();
                // Labels will be hours (00:00, 01:00, etc.)
                $labels = Collection::times(24, fn($i) => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00')->toArray();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY); // Start from Monday
                $endDate = Carbon::now()->endOfWeek(Carbon::SUNDAY);     // End on Sunday
                // Labels will be short day names (Mon, Tue, etc.)
                $labels = Collection::times(7, fn ($i) => Carbon::parse($startDate)->addDays($i)->format('D, M d'))->toArray();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                // Labels will be day numbers (1, 2, ..., 31)
                $labels = Collection::times($endDate->day, fn ($i) => Carbon::parse($startDate)->addDays($i)->format('M d'))->toArray();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                $labels = Collection::times($endDate->day, fn ($i) => Carbon::parse($startDate)->addDays($i)->format('M d'))->toArray();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                // Labels will be month abbreviations (Jan, Feb, etc.)
                $labels = Collection::times(12, fn ($i) => Carbon::create(null, $i + 1, 1)->format('M'))->toArray();
                break;
            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear();
                $endDate = Carbon::now()->subYear()->endOfYear();
                $labels = Collection::times(12, fn ($i) => Carbon::create(null, $i + 1, 1)->format('M'))->toArray();
                break;
            default: // Fallback to this_month if filter is somehow invalid
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $labels = Collection::times($endDate->day, fn ($i) => Carbon::parse($startDate)->addDays($i)->format('M d'))->toArray();
                break;
        }

        // Query base for completed orders within the determined date range
        $query = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Fetch data based on the chosen filter granularity
        if ($this->filter === 'today') {
            $rawHourlyData = $query->select(
                DB::raw('HOUR(created_at) as period'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('period')
            ->pluck('count', 'period')
            ->toArray();

            // Fill in missing hours with 0
            $dataCounts = [];
            for($i = 0; $i < 24; $i++) {
                $dataCounts[] = $rawHourlyData[$i] ?? 0;
            }

        } elseif (in_array($this->filter, ['this_week', 'this_month', 'last_month'])) {
            $rawDailyData = $query->select(
                DB::raw('DATE(created_at) as period'), // Format 'YYYY-MM-DD'
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('period')
            ->pluck('count', 'period')
            ->toArray();

            // Fill in missing days with 0
            $period = Carbon::parse($startDate)->toPeriod($endDate, '1 day');
            foreach ($period as $date) {
                $dataCounts[] = $rawDailyData[$date->format('Y-m-d')] ?? 0;
            }

        } elseif (in_array($this->filter, ['this_year', 'last_year'])) {
            $rawMonthlyData = $query->select(
                DB::raw('MONTH(created_at) as period'), // Month number (1-12)
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('period')
            ->pluck('count', 'period')
            ->toArray();

            // Fill in missing months with 0
            $dataCounts = [];
            for ($i = 1; $i <= 12; $i++) { // Months are 1-indexed
                $dataCounts[] = $rawMonthlyData[$i] ?? 0;
            }
        }


        return [
            'datasets' => [
                [
                    'label' => 'Number of Orders', // Label for the dataset in the legend
                    'data' => $dataCounts,
                    'borderColor' => '#0F172A', // Example line color (Tailwind gray-900)
                    'backgroundColor' => 'rgba(15, 23, 42, 0.1)', // Light fill color
                    'tension' => 0.4, // Makes the line curved
                ],
            ],
            'labels' => $labels,
        ];
    }

    // Optional: Configure chart options for better presentation
    protected function getOptions(): array
{
    return [
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'stepSize' => 1,
                ],
            ],
        ],
        'plugins' => [
            'tooltip' => [
                'callbacks' => [
                    'title' => 'function(context) { return context[0].label; }',
                    'label' => 'function(context) { return "Completed Orders: " + context.raw; }',
                ],
            ],
        ],
        'responsive' => true,
        'maintainAspectRatio' => false,
    ];
}


}