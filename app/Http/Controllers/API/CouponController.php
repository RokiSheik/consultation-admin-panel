<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        // Validate request
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        // Find the coupon
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon code not found.'
            ], 404);
        }

        // Check expiry
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon code has expired.'
            ], 400);
        }

        // Return coupon details
        return response()->json([
            'success' => true,
            'data' => [
                'discount_amount' => $coupon->discount_amount,
                'discount_type' => $coupon->discount_type,
                'applies_to' => $coupon->applies_to,
                'expires_at' => $coupon->expires_at?->toDateString(),
            ],
        ]);
    }
}
