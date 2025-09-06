<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    // List all contents
    public function index()
    {
        return response()->json(Content::all());
    }

    // Get single content
    public function show($id)
    {
        $content = Content::find($id);

        if (!$content) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        return response()->json($content);
    }

    // Store new content (optional)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|string',
            'video_url' => 'nullable|string',
            'text' => 'nullable|string',
            'large_image' => 'nullable|string',
            'author' => 'nullable|array',
            'date' => 'nullable|string',
            'view' => 'nullable|string',
            'categories' => 'nullable|array',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|array',
            'tags' => 'nullable|array',
        ]);

        $content = Content::create($data);

        return response()->json([
            'message' => 'Content created successfully',
            'content' => $content,
        ], 201);
    }
}
