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



        // $response = Http::asForm()->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', $post_data);
        // $response = Http::asForm()->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', array_merge($post_data, [
        //     'checkout_url' => 'easy', // This tells SSLC to load Easy Checkout
        // ]));

        // Get response as array
        // $responseData = $response->json();

        // // Log it for inspection
        // \Log::info('SSLCommerz Full Response:', $responseData);
        // \Log::info('SSLCommerz Request Data:', $post_data);

        // // Return full response to Postman
        // return response()->json([
        //     'sslcommerz_response' => $responseData,
        //     'payment_url' => $responseData['GatewayPageURL'] ?? null
        // ]);

        $response = Http::asForm()->post('https://securepay.sslcommerz.com/gwprocess/v4/api.php', $post_data);

        // Decode the response JSON
        $responseData = $response->json();

        // Optional: log the full response for debugging
        // \Log::info('SSLCommerz Response:', $responseData);
        // \Log::info('SSLCommerz Full Response for Session Creation:', $responseData);

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



}