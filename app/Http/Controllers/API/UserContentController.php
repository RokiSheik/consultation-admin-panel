<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Import the Log facade

class UserContentController extends Controller
{
    public function index(Request $request)
    {
        // Use a log to record the incoming request data
        Log::info('API request received with data:', $request->all());

        $customerId = $request->input('customer_id');

        $query = Order::query()
            ->where('status', 'completed')
            ->where('service_name', 'Content')
            ->whereIn('package_type', ['Basic', 'Business', 'Advanced']);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $orders = $query->get()->map(function ($order) {
            $accessDays = match ($order->package_type) {
                'Basic' => 30,
                'Business' => 90,
                'Advanced' => 365,
                default => 0
            };

            $orderDate = Carbon::parse($order->order_date)->startOfDay();
            $expiryDate = $orderDate->copy()->addDays($accessDays);
            $now = Carbon::now()->startOfDay();

            $remainingDays = $now->lt($expiryDate)
                ? $now->diffInDays($expiryDate)
                : 0;

            return [
                'order_date' => $orderDate->toDateString(),
                'remaining_days' => $remainingDays,
                'expired' => $remainingDays === 0,
            ];
        });

        return response()->json($orders);
    }
}