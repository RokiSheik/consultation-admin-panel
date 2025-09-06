<?php

namespace App\Filament\Widgets;

use App\Models\Order; // Make sure this path is correct for your Order model
use Carbon\Carbon;    // Import Carbon for date manipulation
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB; // For raw database queries (e.g., HOUR(), DATE(), MONTH())
use Illuminate\Support\Collection; // Import Collection for proper usage

class CompletedOrdersRevenueChart extends ChartWidget
{
    // Livewire property to hold the selected filter period.
    public ?string $filter; 

    // Chart heading specific to revenue
    protected static ?string $heading = 'All Revenue'; 

    // Set the widget to span the full width of the dashboard
    // protected int | string | array $columnSpan = 'full'; 
    
    // Explicitly set a minimum height for the chart container to ensure it's visible.
    // protected static ?string $minHeight = '300px'; 

    // Initialize the filter property when the Livewire component mounts
    public function mount(): void
    {
        $this->filter = 'this_month'; // Default filter
    }

    // Define the chart type as 'bar'
    protected function getType(): string
    {
        return 'bar'; // This widget will be a bar chart
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
        $endDate = Carbon::now();
        
        // Initialize labels and data arrays here; they will be populated below
        $labels = []; 
        $dataRevenue = []; 

        // Determine start and end dates based on the selected filter
        switch ($this->filter) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
                $endDate = Carbon::now()->endOfWeek(Carbon::SUNDAY);
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear();
                $endDate = Carbon::now()->subYear()->endOfYear();
                break;
            default: // Fallback to this_month if filter is somehow invalid
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        // Base query for completed orders within the determined date range
        $query = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Fetch data based on the chosen filter granularity, summing 'total_amount' for revenue
        if ($this->filter === 'today') {
            $rawHourlyData = $query->select(
                DB::raw('HOUR(created_at) as period'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('period')
            ->pluck('revenue', 'period')
            ->toArray();

            // Populate labels and dataRevenue for 'today' (hourly)
            for($i = 0; $i < 24; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $dataRevenue[] = $rawHourlyData[$i] ?? 0;
            }

        } elseif (in_array($this->filter, ['this_week', 'this_month', 'last_month'])) {
            $rawDailyData = $query->select(
                DB::raw('DATE(created_at) as period'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('period')
            ->pluck('revenue', 'period')
            ->toArray();

            // Populate labels and dataRevenue for daily periods
            $period = Carbon::parse($startDate)->toPeriod($endDate, '1 day');
            foreach ($period as $date) {
                $labels[] = $date->format('M d'); // e.g., 'Jun 22'
                $dataRevenue[] = $rawDailyData[$date->format('Y-m-d')] ?? 0; // Use 'Y-m-d' as key
            }

        } elseif (in_array($this->filter, ['this_year', 'last_year'])) {
            $rawMonthlyData = $query->select(
                DB::raw('MONTH(created_at) as period'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('period')
            ->pluck('revenue', 'period')
            ->toArray();

            // Populate labels and dataRevenue for yearly periods (months)
            for ($i = 1; $i <= 12; $i++) { // Months are 1-indexed
                $labels[] = Carbon::create(null, $i, 1)->format('M'); // e.g., 'Jan'
                $dataRevenue[] = $rawMonthlyData[$i] ?? 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Revenue',
                    'data' => $dataRevenue,
                    'backgroundColor' => 'rgba(33, 10, 121, 0.7)',
                    'borderColor' => 'rgba(33, 10, 121, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
{
    return [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'plugins' => [
            'tooltip' => [
                'enabled' => true,
                'callbacks' => [
                    'title' => 'function(context) { return context[0].label; }',
                    'label' => 'function(context) { return "Count: " + context.raw; }',
                ],
            ],
        ],
    ];
}


}