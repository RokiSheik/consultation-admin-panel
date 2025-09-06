<?php

namespace App\Filament\Resources\RevenueResource\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class TotalRevenueWidget extends Widget
{
    protected static string $view = 'filament.resources.revenue-resource.widgets.total-revenue-widget';

    public float $totalRevenue = 0;

    public function mount()
    {
        $this->calculateTotalRevenue('all_time');
    }

    public function calculateTotalRevenue(string $filter)
    {
        $query = Order::where('status', 'completed');
        $now = Carbon::now();

        switch ($filter) {
            case 'today':
                $query->whereDate('order_date', $now);
                break;

            case 'this_week':
                $query->whereBetween('order_date', [$now->startOfWeek(), $now->endOfWeek()]);
                break;

            case 'this_month':
                $query->whereBetween('order_date', [$now->startOfMonth(), $now->endOfMonth()]);
                break;

            case 'last_month':
                $query->whereBetween('order_date', [
                    $now->copy()->subMonth()->startOfMonth(),
                    $now->copy()->subMonth()->endOfMonth(),
                ]);
                break;

            case 'last_year':
                $query->whereBetween('order_date', [
                    $now->copy()->subYear()->startOfYear(),
                    $now->copy()->subYear()->endOfYear(),
                ]);
                break;

            case 'all_time':
            default:
                // No filter
                break;
        }

        $this->totalRevenue = (float) $query->sum('total_amount');
    }
}
