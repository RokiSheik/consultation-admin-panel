<?php

use App\Http\Controllers\ConsultingFormController;
use App\Http\Controllers\CourseProgressController;
use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\API\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\OrderController;
 use App\Http\Controllers\API\SubscriberController;
 use App\Http\Controllers\ContactController;
 use App\Http\Controllers\API\PodcastRequestController;
 use App\Http\Controllers\ConsultingRequestController;
 use App\Http\Controllers\API\TrainingApplicationController;
 use App\Http\Controllers\API\SpeakingRequestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/consulting/book', [ConsultingFormController::class, 'bookConsultant']);
Route::post('/consulting/become', [ConsultingFormController::class, 'becomeConsultant']);

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{slug}', [CourseController::class, 'show']);

Route::middleware('auth:customer')->group(function () {
    Route::get('/contents', [ContentController::class, 'index']);
    Route::get('/contents/{id}', [ContentController::class, 'show']);
    Route::post('/create-order', [OrderController::class, 'create']);
    // Route::post('/orders', [OrderController::class, 'index']);

});
Route::get('/orders', [OrderController::class, 'index']);

Route::middleware('auth:customer')->group(function () {
    Route::get('/courses/{slug}/progress', [CourseProgressController::class, 'getProgress']);
    Route::post('/courses/{slug}/progress', [CourseProgressController::class, 'updateProgress']);
});


Route::prefix('customer')->group(function () {
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::get('/verify/{token}', [CustomerAuthController::class, 'verifyCustomer']);
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::post('/forgot-password', [CustomerAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [CustomerAuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [CustomerAuthController::class, 'logout']);
        Route::put('/update', [CustomerAuthController::class, 'updateProfile']);

    });

});

Route::post('/sslcommerz/success', [OrderController::class, 'success']);
Route::post('/sslcommerz/fail', [OrderController::class, 'fail']);
Route::post('/sslcommerz/cancel', [OrderController::class, 'cancel']);



Route::get('subscribers', [SubscriberController::class, 'index']);
Route::post('subscribers', [SubscriberController::class, 'store']);

Route::post('/contact', [ContactController::class, 'store']);
Route::post('/podcast-requests', [PodcastRequestController::class, 'store']);
Route::post('/training-request', [TrainingApplicationController::class, 'store']);
Route::post('/speaking-request', [SpeakingRequestController::class, 'store']);


use App\Http\Controllers\API\AffiliatorApiController;

Route::get('/affiliator-stats/{customer_id}', [AffiliatorApiController::class, 'show']);

use App\Http\Controllers\API\UserContentController;

Route::get('/user/content-access', [UserContentController::class, 'index']);
use App\Http\Controllers\API\CourseOrderController;

Route::get('/user/{customer_id}/courses', [CourseOrderController::class, 'getCustomerCourses']);

Route::post('/consulting-request', [ConsultingRequestController::class, 'store']);


use App\Http\Controllers\Api\PdfFormSubmissionController;

Route::post('/pdf', [PdfFormSubmissionController::class, 'store']);

use App\Http\Controllers\AffiliateRequestController;

Route::post('/affiliate-requests', [AffiliateRequestController::class, 'store']);

use App\Http\Controllers\API\CouponController;

Route::post('/validate-coupon', [CouponController::class, 'validateCoupon']);


