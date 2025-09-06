<?php

namespace App\Http\Controllers;

use App\Models\ConsultingRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsultingRequestController extends Controller
{
    /**
     * Store a newly created consulting request in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'full_name' => 'required|string|max:255',
                'email_address' => 'required|email|max:255|unique:consulting_requests,email_address',
                'phone_number' => 'required|string|max:20',
                'whatsapp_number' => 'required|string|max:20',
                'company_name' => 'nullable|string|max:255',
                'website_social_media' => 'nullable|url|max:255',
                'city_country' => 'required|string|max:255',
                'age' => 'required|integer|min:18',
                'how_heard_about_us' => 'required|string|max:255',
                'how_heard_about_us_other' => 'nullable|string|max:255',
                'applying_for' => 'required|string|max:255',
                'applying_for_other' => 'nullable|string|max:255',
                'best_describes_you' => 'required|string|max:255',
                'best_describes_you_other' => 'nullable|string|max:255',
                'current_business_description' => 'required|string',
                'current_monthly_revenue' => [
                    'required',
                    Rule::in(["Below 1 Lakh BDT", "1–5 Lakh BDT", "5–20 Lakh BDT", "20 Lakh - 1 Crore BDT", "Above 1 Crore BDT"]),
                ],
                'business_duration' => [
                    'required',
                    Rule::in(["Less than 1 year", "1–3 years", "3–7 years", "7+ years"]),
                ],
                'priority_next_months' => 'required|string|max:255',
                'priority_next_months_other' => 'nullable|string|max:255',
                'biggest_challenges' => 'required|string|max:255',
                'biggest_challenges_other' => 'nullable|string|max:255',
                'stopped_fixing_challenges' => 'required|string',
                'specific_outcome' => 'required|string|max:255',
                'specific_outcome_other' => 'nullable|string|max:255',
                'commitment_level' => [
                    'required',
                    Rule::in(["I’m just exploring", "I’m interested but not urgent", "I’m fully committed and ready now"]),
                ],
                'willing_to_invest' => [
                    'required',
                    Rule::in(["No", "Yes – with a limited budget", "Yes – I’m looking for high ROI, not low price"]),
                ],
                'investment_budget' => [
                    'required',
                    Rule::in(["Below 50,000 BDT", "50,000–500000 Lakh BDT", "5–15 Lakh BDT", "15 Lakh+ BDT"]),
                ],
                'book_discovery_call' => [
                    'required',
                    Rule::in(["Yes", "No, I want direct consulting"]),
                ],
                'specific_request' => 'nullable|string',
            ]);

            // Create a new ConsultingRequest record
            $consultingRequest = ConsultingRequest::create($validatedData);

            // Return a success response
            return response()->json([
                'message' => 'Consulting application submitted successfully!',
                'data' => $consultingRequest,
            ], 201); // 201 Created status
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422); // 422 Unprocessable Entity status
        } catch (\Exception $e) {
            // Return a generic error response for other exceptions
            return response()->json([
                'message' => 'An error occurred during submission.',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }
}