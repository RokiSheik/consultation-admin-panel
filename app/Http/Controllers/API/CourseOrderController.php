<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Course;

class CourseOrderController extends Controller
{
    public function getCustomerCourses($customer_id)
    {
        // Get all course orders for the customer
        $courseOrders = Order::where('customer_id', $customer_id)
            ->where('service_name', 'Course')
            ->where('status', 'completed') // ✅ Only completed orders
            ->pluck('package_type'); // This will get all course titles

        // Get all course details based on package_type = title
        $courses = Course::whereIn('title', $courseOrders)->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
}

