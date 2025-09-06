<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookConsultant;
use App\Models\BecomeConsultant;

class ConsultingFormController extends Controller
{
    // Book a Speaker form submission
    public function bookConsultant(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'company'    => 'required|string|max:255',
            'title'      => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',
            'interested_speaker' => 'nullable|string|max:255',
            'event_name' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'event_location' => 'required|string|max:255',
            'event_budget' => 'nullable|string|max:50',
            'event_website' => 'nullable|url',
            'additional_info' => 'nullable|string',
            'receive_updates' => 'boolean'
        ]);

        BookConsultant::create($data);

        return response()->json(['message' => 'Book a Consultant form submitted successfully!'], 201);
    }

    // Become a Speaker form submission
    public function becomeConsultant(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'company'    => 'required|string|max:255',
            'title'      => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',
            'website_link' => 'nullable|url',
            'video_link' => 'nullable|url',
            'speaking_fees' => 'nullable|string|max:255',
            'speaking_experience' => 'nullable|string',
            'topics' => 'nullable|string',
            'why_join' => 'nullable|string',
            'receive_updates' => 'boolean'
        ]);

        BecomeConsultant::create($data);

        return response()->json(['message' => 'Become a Consultant form submitted successfully!'], 201);
    }
}
