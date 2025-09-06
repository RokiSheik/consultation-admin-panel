<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => asset('storage/' . $this->image),
            'videoUrl' => $this->video_url,
            'regularPrice' => $this->regular_price,
            'price' => $this->price,
            'rating' => $this->rating,
            'totalStudent' => $this->total_student,
            'author' => $this->author,
            'date' => $this->date ? \Carbon\Carbon::parse($this->date)->format('d M Y') : null,
            'tags' => $this->tags ?? [],
            'content' => $this->content ?? [],
            'details' => explode('</p>', $this->details ?? '') ?? [],
            'playlist' => $this->playlistVideos->map(function ($video) {
                return [
                    'title' => $video->title,
                    'url' => $video->video_url,
                    'order' => $video->order,
                ];
            }),
        ];
    }
}
