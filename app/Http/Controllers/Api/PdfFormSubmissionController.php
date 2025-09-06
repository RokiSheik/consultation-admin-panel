<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PdfFormSubmission;
use Illuminate\Support\Facades\Validator;

class PdfFormSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone_number'    => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'profession'      => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'message'         => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submission = PdfFormSubmission::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Form submitted successfully!',
            'data' => $submission
        ], 201);
    }
}

