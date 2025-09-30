<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use App\Notifications\OrderSuccessNotification;


class OrderController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string',
            'package_type' => 'required|string',
            'amount' => 'required|numeric',
            'affiliator' => 'nullable|string',
        ]);

        $customer = auth()->guard('customer')->user();
        $transaction_id = 'TRX_' . uniqid();

        $affiliatorId = $request->input('affiliator_id'); // this can come from query or frontend

        $order = Order::create([
            'customer_id' => $customer->id,
            'affiliator_id' => $affiliatorId, // <- new
            'service_name' => $request->service_name,
            'package_type' => $request->package_type,
            'total_amount' => $request->amount,
            'status' => 'pending',
            'order_date' => now(),
            'transaction_id' => $transaction_id,
        ]);
        if ($order->status === 'pending' && $order->service_name === 'Community') {
            $customer->notify(new \App\Notifications\OrderPendingNotification($order));
        }

        // Check for free course orders
if ($request->service_name === 'Course' && (float) $request->amount === 0.0) {
    // Mark order as completed
    $order->update(['status' => 'completed']);

    // Notify customer
    $customer->notify(new OrderSuccessNotification($order));

    return response()->json([
        'success' => true,
        'message' => 'Free course enrolled successfully.',
        'order' => $order,
    ]);
}


        $post_data = [
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'total_amount' => (float) $order->total_amount, // Ensure it's a float
            'currency' => "BDT",
            'tran_id' => $transaction_id,
            'success_url' => url('/api/sslcommerz/success'),
            'fail_url' => url('/api/sslcommerz/fail'),
            'cancel_url' => url('/api/sslcommerz/cancel'),
            'return_url' => 'https://passionateworlduniversity.com/order-success',
            'cus_name' => $customer->name,
            'cus_email' => $customer->email,
            'cus_add1' => $customer->address,
            'cus_phone' => $customer->phone_number,
            'cus_city' => "Rajshahi",
            'cus_country' => "Bangladesh",
            'shipping_method' => "NO",
            'product_name' => $request->package_type,
            'product_category' => $request->service_name,
            'product_profile' => "general",
            'checkout_url' => 'easy',
        ];

        $response = Http::asForm()->post('https://securepay.sslcommerz.com/gwprocess/v4/api.php', $post_data);

        // Decode the response JSON
        $responseData = $response->json();

        // Check if sessionkey is present
        if (!empty($responseData['status']) && $responseData['status'] === 'SUCCESS') {
            return response()->json([
                'sessionkey' => $responseData['sessionkey'],
                'payment_url' => $responseData['GatewayPageURL'] ?? null
            ]);
        } else {
            return response()->json([
                'message' => $responseData['failedreason'] ?? 'SSLCommerz Payment Initialization Failed',
                'error' => true
            ], 422);
        }



    }

    public function success(Request $request)
    {
        $order = Order::where('transaction_id', $request->tran_id)->first();

        if ($order) {
            $order->update(['status' => 'completed']);

            $customer = $order->customer; // Make sure you have this relationship

            $customer->notify(new OrderSuccessNotification($order));


        }

        return redirect('https://passionateworlduniversity.com/order-success');
    }

    public function fail(Request $request)
    {
        $order = Order::where('transaction_id', $request->tran_id)->first();
        if ($order)
            $order->update(['status' => 'cancelled']);
        return redirect('https://passionateworlduniversity.com/order-cancelled');
    }

    public function cancel(Request $request)
    {
        $order = Order::where('transaction_id', $request->tran_id)->first();
        if ($order)
            $order->update(['status' => 'cancelled']);
        return redirect('https://passionateworlduniversity.com/cancel-success');
    }

   public function index(Request $request)
{
    $customerId = $request->query('customer_id'); // ?customer_id=5

    $orders = Order::where('customer_id', $customerId)
                   ->with('customer')
                   ->latest()
                   ->get();

    return response()->json([
        'success' => true,
        'data' => $orders,
    ]);
}

public function submitLandingForm(Request $request)
{
    // Step 1: Validate form input
    $request->validate([
        'name' => 'required|string|max:255',
        'whatsapp' => 'required|string|max:20',
        'profession' => 'nullable|string|max:255',
        'business_type' => 'nullable|string|max:255',
        'current_revenue' => 'nullable|string|max:255',
        'future_revenue' => 'nullable|string|max:255',
        'total_team' => 'nullable|integer',
        'landing_title' => 'required|string|max:255',
        'offer_price' => 'required|numeric',
    ]);

    // Step 2: Store lead
    $lead = \App\Models\Lead::create([
        'name' => $request->name,
        'whatsapp' => $request->whatsapp,
        'profession' => $request->profession,
        'business_type' => $request->business_type,
        'current_revenue' => $request->current_revenue,
        'future_revenue' => $request->future_revenue,
        'total_team' => $request->total_team,
        'landing_title' => $request->landing_title,
    ]);

    // Step 3: Create order
    $transaction_id = 'TRX_' . uniqid();
    $pseudoCustomerId = 10000 + $lead->id;

    $order = \App\Models\Order::create([
        'customer_id' => $pseudoCustomerId,
        'customer_phone' => $lead->whatsapp,
        'service_name' => 'Campaign',
        'package_type' => $request->landing_title,
        'total_amount' => $request->offer_price,
        'status' => 'pending',
        'order_date' => now(),
        'transaction_id' => $transaction_id,
    ]);

    // Step 4: Prepare SSLCommerz data
    $post_data = [
        'store_id' => config('services.sslcommerz.store_id'),
        'store_passwd' => config('services.sslcommerz.store_password'),
        'total_amount' => (float)$order->total_amount,
        'currency' => 'BDT',
        'tran_id' => $transaction_id,
        'success_url' => url('/api/sslcommerz/success'),
        'fail_url' => url('/api/sslcommerz/fail'),
        'cancel_url' => url('/api/sslcommerz/cancel'),
        'return_url' => 'https://passionateworlduniversity.com/order-success',
        'cus_name' => $request->name,
        'cus_phone' => $request->whatsapp,
        'cus_add1' => "N/A",
        'cus_email' => "N/A",
        'cus_city' => "Rajshahi",
        'cus_country' => "Bangladesh",
        'shipping_method' => "NO",
        'product_name' => $order->service_name,
        'product_category' => $order->package_type,
        'product_profile' => "general",
        'checkout_url' => 'easy',
    ];

    // Step 5: Call SSLCommerz API
    $response = \Illuminate\Support\Facades\Http::asForm()->post('https://securepay.sslcommerz.com/gwprocess/v4/api.php', $post_data);
    $responseData = $response->json();

    if (!empty($responseData['GatewayPageURL'])) {
        return response()->json([
            'success' => true,
            'payment_url' => $responseData['GatewayPageURL']
        ]);
    }

    return response()->json([
        'error' => true,
        'message' => $responseData['failedreason'] ?? 'Payment initiation failed'
    ], 422);
}



}