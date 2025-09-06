<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Notifications\WelcomeEmail;

use App\Models\CustomerVerification;
use App\Notifications\VerifyCustomerEmail;

class CustomerAuthController extends Controller
{
    // ✅ Register
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:customers,email',
    //         'phone_number' => 'nullable|string',
    //         'whatsapp_number' => 'nullable|string',
    //         'address' => 'nullable|string',
    //         'profession' => 'nullable|string',
    //         'password' => 'required|confirmed|min:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $customer = Customer::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone_number' => $request->phone_number,
    //         'whatsapp_number' => $request->whatsapp_number,
    //         'address' => $request->address,
    //         'profession' => $request->profession,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     $customer->notify(new WelcomeEmail($customer->name));

    //     return response()->json(['message' => 'Customer registered successfully'], 201);
    // }

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone_number' => 'nullable|string',
        'whatsapp_number' => 'nullable|string',
        'address' => 'nullable|string',
        'profession' => 'nullable|string',
        'password' => 'required|confirmed|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Generate a verification token
    $token = Str::random(64);

    // Save details temporarily
    CustomerVerification::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'whatsapp_number' => $request->whatsapp_number,
        'address' => $request->address,
        'profession' => $request->profession,
        'password' => Hash::make($request->password),
        'token' => $token,
    ]);

    // Send verification email
    \Notification::route('mail', $request->email)
        ->notify(new VerifyCustomerEmail($token, $request->email));

    return response()->json([
        'message' => 'Verification email sent. Please check your inbox to verify your account.'
    ], 201);
}


public function verifyCustomer($token, Request $request)
{
    $verification = CustomerVerification::where('token', $token)
                        ->where('email', $request->email)
                        ->first();

    if (!$verification) {
        return response()->json(['message' => 'Invalid or expired verification link.'], 400);
    }

    // Create actual customer
    $customer = Customer::create([
        'name' => $verification->name,
        'email' => $verification->email,
        'phone_number' => $verification->phone_number,
        'whatsapp_number' => $verification->whatsapp_number,
        'address' => $verification->address,
        'profession' => $verification->profession,
        'password' => $verification->password,
    ]);

    // Optionally send welcome email
    $customer->notify(new WelcomeEmail($customer->name));

    // Delete temp verification record
    $verification->delete();

    return response()->json(['message' => 'Email verified successfully. You can now log in.']);
}


    // ✅ Login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $customer = Customer::where('email', $request->email)->first();

            if (!$customer || !Hash::check($request->password, $customer->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Check if Customer model has HasApiTokens trait
            $token = $customer->createToken('customer-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'customer' => $customer
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Login failed',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }


    // ✅ Forgot Password (Send Email)

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email|exists:customers,email']);

            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $token,
                    'created_at' => now(),
                ]
            );

            $customer = Customer::where('email', $request->email)->first();

            // ✅ Make sure Customer model uses Notifiable
            $customer->notify(new \App\Notifications\CustomerResetPassword($token, $request->email));

            return response()->json(['message' => 'Password reset email sent.']);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to send reset email',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }




    // ✅ Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($customer, $password) {
                $customer->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($customer));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been reset.'])
            : response()->json(['message' => 'Failed to reset password.'], 400);
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function updateProfile(Request $request)
{
    $customer = $request->user();

    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|max:255',
        'phone_number' => 'nullable|string|max:20',
        'whatsapp_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'profession' => 'nullable|string|max:100',
        'email' => 'nullable|email|unique:customers,email,' . $customer->id,

        // Only validate password if being changed
        'current_password' => 'required_with:password|string',
        'password' => 'nullable|confirmed|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // If password is being changed, verify the current password
    if ($request->filled('password')) {
        if (!Hash::check($request->current_password, $customer->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 403);
        }

        $customer->password = Hash::make($request->password);
    }

    // Update other fields if present
    $customer->name = $request->input('name', $customer->name);
$customer->phone_number = $request->input('phone_number', $customer->phone_number);
$customer->whatsapp_number = $request->input('whatsapp_number', $customer->whatsapp_number);
$customer->address = $request->input('address', $customer->address);
$customer->profession = $request->input('profession', $customer->profession);
$customer->email = $request->input('email', $customer->email);

    $customer->save();

    return response()->json(['message' => 'Profile updated successfully', 'customer' => $customer]);
}

}

