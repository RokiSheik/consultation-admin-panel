<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\Order;
use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;

class AffiliatorApiController extends Controller
{
    public function show($customerId): JsonResponse
    {
        // Step 1: Find the customer by ID
        $customer = Customer::find($customerId);

        if (! $customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        // Step 2: Find affiliator by customer email
        $affiliator = Affiliator::where('email', $customer->email)->first();

        if (! $affiliator) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliator not found for this customer email.',
            ], 404);
        }

        // Step 3: Fetch orders and withdrawals
        $orders = Order::where('affiliator_id', $affiliator->affiliator_id)->get();
        $withdrawals = Withdrawal::where('affiliator_id', $affiliator->id)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'affiliator_id' => $affiliator->affiliator_id,
                'balance' => $affiliator->balance,
                'sells' => $orders->map(function ($order) {
                    return [
                        'order_id' => $order->id,
                        'service' => $order->service_name,
                        'package' => $order->package_type,
                        'amount' => $order->total_amount,
                        'status' => $order->status,
                        'date' => $order->order_date,
                    ];
                }),
                'withdrawals' => $withdrawals->map(function ($withdrawal) {
                    return [
                        'withdrawal_id' => $withdrawal->id,
                        'amount' => $withdrawal->amount,
                        'status' => $withdrawal->status,
                        'requested_at' => $withdrawal->created_at->toDateTimeString(),
                    ];
                }),
            ],
        ]);
    }
}
