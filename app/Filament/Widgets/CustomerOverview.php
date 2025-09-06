<?php

namespace App\Filament\Widgets;

use App\Models\Content;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Customer; // Make sure this path is correct for your Customer model
use App\Models\Course;   // Assuming you have a Course model
use App\Models\Order;    // Assuming you have an Order model

class CustomerOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Fetch data for your stats
        $totalCustomers = Customer::count();
        $totalContent = Content::count();
        $totalCourses = Course::count();
        $totalCompletedOrders = Order::where('status', 'completed')->count();

        // Calculate Total Revenue
        // Assuming 'total_amount' is the column that stores the revenue for each order
        // Calculate Total Revenue for Completed Orders
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // Calculate Total Revenue for Completed 'Course' Orders
        $totalRevenueCourse = Order::where('status', 'completed')
            ->where('service_name', 'Course')
            ->sum('total_amount');
        // Sums 'total_amount' only for 'Course' service_type

        return [
            // 1. Customer Stat
            Stat::make("Customers", $totalCustomers)
                ->description("Total Customers Gained")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([90, 50, 10, 50, 80, 50, 20, 30, 20, 70, 100])
                ->color('success'),

            // 2. Total Course Stat
            Stat::make("Total Course", $totalCourses)
                ->description("All Courses")
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

             // 2. Total Content Stat
            Stat::make("Total Content", $totalCourses)
                ->description("All Content")
                ->descriptionIcon('heroicon-o-rectangle-group')
                ->color('info'),

            // 3. Total Completed Order Stat
            Stat::make("Completed Orders", $totalCompletedOrders)
                ->description("Orders Successfully Finished")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),

            // 4. Total Revenue Stat (New)
            Stat::make("Total Revenue", '৳' . number_format($totalRevenue, 2)) // Format as currency
                ->description("Overall Revenue Generated")
                ->descriptionIcon('heroicon-m-banknotes') // Example icon for money
                ->color('warning'), // You can choose a different color

            // 5. Total Revenue for Service Type Course Stat (New)
            Stat::make("Revenue (Courses)", '৳' . number_format($totalRevenueCourse, 2)) // Format as currency
                ->description("Revenue from Course Sales")
                ->descriptionIcon('heroicon-m-currency-dollar') // Example icon for money
                ->color('danger'), // You can choose a different color
        ];
    }
}