<?php

namespace App\Filament\Resources\RevenueResource\Pages;

use App\Models\Order;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RevenueResource;
use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ListRevenues extends ListRecords
{
    protected static string $resource = RevenueResource::class;

    // Position header actions before the table for better wrapping
    protected function getHeaderActionsPosition(): string
    {
        return 'before';
    }

    protected function getTableQuery(): ?Builder
    {
        return Order::query()->where('status', 'completed');
    }

    protected function getHeaderActions(): array
{
    $now = Carbon::now();

    $total = Order::where('status', 'completed')->sum('total_amount');
    $today = Order::whereDate('order_date', $now)->where('status', 'completed')->sum('total_amount');
    $thisWeek = Order::whereBetween('order_date', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->where('status', 'completed')->sum('total_amount');
    $thisMonth = Order::whereMonth('order_date', $now->month)->whereYear('order_date', $now->year)->where('status', 'completed')->sum('total_amount');
    $lastMonth = Order::whereMonth('order_date', $now->copy()->subMonth()->month)->whereYear('order_date', $now->copy()->subMonth()->year)->where('status', 'completed')->sum('total_amount');
    $lastYear = Order::whereYear('order_date', $now->copy()->subYear()->year)->where('status', 'completed')->sum('total_amount');

    $actions = [
        Action::make('total')->label('Total Revenue: ৳' . number_format($total, 2))->disabled()->color('success'),
        Action::make('today')->label('Today: ৳' . number_format($today, 2))->disabled()->color('gray'),
        Action::make('this_week')->label('This Week: ৳' . number_format($thisWeek, 2))->disabled()->color('gray'),
        Action::make('this_month')->label('This Month: ৳' . number_format($thisMonth, 2))->disabled()->color('gray'),
        Action::make('last_month')->label('Last Month: ৳' . number_format($lastMonth, 2))->disabled()->color('gray'),
        Action::make('last_year')->label('Last Year: ৳' . number_format($lastYear, 2))->disabled()->color('gray'),
    ];

    foreach ($actions as $action) {
        $action->extraAttributes([
            'class' => 'w-full sm:w-1/3 md:w-1/4 lg:w-1/4 mb-2 sm:mb-0 sm:mr-2 whitespace-nowrap',
            'style' => 'min-width: 120px;', // minimum width so they don't shrink too small
        ]);
    }

    return $actions;
}

}
