<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PodcastRequest;
use Illuminate\Support\Facades\Validator;

class PodcastRequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'podcast_name' => 'required|string',
            'full_name' => 'required|string',
            'phone_number' => 'required|string',
            'whatsapp_number' => 'required|string',
            'email' => 'required|email',
            'website_url' => 'required|string',
            'social_media_links' => 'nullable|string',
            'podcast_description' => 'nullable|string',
            'request_speaker' => 'nullable|string',
            'interview_mode' => 'nullable|string',
            'reason_for_guest' => 'nullable|string',
            'interview_length' => 'nullable|string',
            'talking_points' => 'nullable|string',
            'average_views' => 'nullable|string',
            'media_presence_agreement' => 'required|boolean',
            'share_raw_footage_agreement' => 'required|boolean',
            'final_approval_agreement' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $podcastRequest = PodcastRequest::create($validator->validated());

        return response()->json([
            'message' => 'Podcast request submitted successfully',
            'data' => $podcastRequest,
        ], 201);
    }
}
