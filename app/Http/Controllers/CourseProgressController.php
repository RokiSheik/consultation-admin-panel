<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseProgress;


class CourseProgressController extends Controller
{

    public function getProgress($slug)
    {
        $customer = auth('customer')->user();
        $course = Course::where('slug', $slug)->firstOrFail();

        $progress = \App\Models\CourseProgress::where('customer_id', $customer->id)
            ->where('course_id', $course->id)
            ->first();

        return response()->json([
            'completed_videos' => $progress ? $progress->completed_videos : [],
        ]);
    }
    public function updateProgress(Request $request, $slug)
    {
        $customer = auth('customer')->user();

        $request->validate([
            'video_index' => 'required|integer',
        ]);

        $course = Course::where('slug', $slug)->firstOrFail();

        $progress = \App\Models\CourseProgress::firstOrCreate([
            'customer_id' => $customer->id,
            'course_id' => $course->id,
        ]);

        $completed = $progress->completed_videos ?? [];

        if (!in_array($request->video_index, $completed)) {
            $completed[] = $request->video_index;
            sort($completed); // Optional: keep array sorted
            $progress->completed_videos = $completed;
            $progress->save();
        }

        return response()->json(['message' => 'Progress updated']);
    }





}
