<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SpeakingRequest;

class SpeakingRequestController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'event_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'business_description' => 'required|string',
            'speaker_request' => 'required|string|max:255',
            'speaking_date' => 'required|date',
            'event_time' => 'required|string',
            'speaking_location' => 'required|string|max:255',
            'speaking_length' => 'required|string|max:255',
            'talking_points' => 'required|string',
            'keynote_speaker' => 'required|in:YES,NO',
            'audience_size' => 'required|string|max:255',
            'advertise_event' => 'required|in:YES,NO',
            'social_media_links' => 'required|string',
            'event_content_distribution' => 'nullable|array',
            'event_content_distribution.*' => 'string|in:social-media,paid-members-area,recording-for-sale,free-group',
            'hotel_flights_agreement' => 'required|in:Yes I Agree',
            'credentials_agreement' => 'required|in:Yes I Agree',
            'powerpoint_access' => 'required|in:Yes,No',
            'raw_footage_agreement' => 'required|in:Yes I Agree',
            'media_presence_agreement' => 'required|in:Yes I Agree',
            'content_approval_agreement' => 'required|in:Yes I Agree',
            'other_engagements' => 'required|string',
            'fee_understanding' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Store the data if validation passes
        try {
            $speakingRequest = SpeakingRequest::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Speaking request submitted successfully!',
                'data' => $speakingRequest
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}