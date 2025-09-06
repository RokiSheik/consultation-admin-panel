<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    // GET /api/courses
    public function index()
    {
        $courses = Course::with('playlistVideos')->latest()->get();
        return CourseResource::collection($courses);
    }

    // GET /api/courses/{slug}
    public function show($slug)
    {
        $course = Course::with('playlistVideos')->where('slug', $slug)->firstOrFail();
        return new CourseResource($course);
    }
}

