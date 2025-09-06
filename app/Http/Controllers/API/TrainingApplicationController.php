<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TrainingApplication;

class TrainingApplicationController extends Controller
{
    /**
     * Store a new training application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for the incoming form data
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email_address' => 'required|email|unique:training_applications,email_address',
            'phone_number' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'city_country' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'current_occupation' => 'nullable|string|max:255',
            'current_work_description' => 'required|string',
            'current_stage_revenue_structure' => 'required|string',
            'average_monthly_income' => 'required|string|max:255',
            'invested_in_development' => 'required|string|in:Yes,No',
            'investment_details' => 'nullable|required_if:invested_in_development,Yes|string',
            'biggest_goal' => 'required|string|max:255',
            'top_challenges' => 'required|string',
            'program_applying_for' => 'required|string|max:255',
            'expected_result' => 'required|string',
            'why_good_fit' => 'required|string',
            'commitment_level' => 'required|string|max:255',
            'ready_to_invest' => 'required|string|max:255',
            'expected_budget' => 'required|string|max:255',
            'how_did_you_hear' => 'required|string|max:255',
            'additional_info' => 'nullable|string',
        ]);
        
        // If validation fails, return a JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create a new application instance with the validated data
            $application = TrainingApplication::create($validator->validated());
            
            // Return a success response
            return response()->json([
                'message' => 'Training application submitted successfully!',
                'data' => $application
            ], 201);

        } catch (\Exception $e) {
            // Catch and handle any server errors
            return response()->json([
                'message' => 'An error occurred while saving the application.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
