<?php

namespace App\Http\Controllers;

use App\Models\AffiliateRequest;
use App\Models\Affiliator;
use Illuminate\Http\Request;

class AffiliateRequestController extends Controller
{
    // Store request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email_address' => 'required|email|unique:affiliate_requests,email_address',
            'phone_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'website_social_media_link' => 'required|string',
            'primary_audience' => 'required|string',
            'followers_subscribers' => 'required|string',
            'promotion_platforms' => 'required|string',
            'reason_to_join' => 'required|string',
            'done_affiliate_marketing' => 'required|string',
            'how_heard_about_us' => 'required|string',
            'agreement' => 'required|boolean',
            'payment_method' => 'nullable|string|max:255',        // ✅ new field
            'payment_method_details' => 'nullable|string',         // ✅ new field
        ]);

        $affiliateRequest = AffiliateRequest::create($validated);

        return response()->json([
            'message' => 'Your request has been submitted and is pending approval.',
            'data' => $affiliateRequest,
        ], 201);
    }

    // Approve request
    public function approve($id)
{
    $request = AffiliateRequest::findOrFail($id);
    $request->update(['status' => 'approved']);

    // ✅ Create a new affiliator or update existing one
    $affiliator = Affiliator::updateOrCreate(
        ['email' => $request->email_address], // match by email
        [
            'name' => $request->full_name,
            'balance' => 0,
            'payment_method' => $request->payment_method,
            'payment_method_details' => $request->payment_method_details,
        ]
    );

    return response()->json(['message' => 'Affiliate approved and added to Affiliators table.']);
}


    // Decline request
    public function decline($id)
    {
        $request = AffiliateRequest::findOrFail($id);
        $request->update(['status' => 'declined']);

        // Remove affiliator if exists
        Affiliator::where('email', $request->email_address)->delete();

        return response()->json(['message' => 'Affiliate request declined and removed from Affiliators table if existed.']);
    }
}
