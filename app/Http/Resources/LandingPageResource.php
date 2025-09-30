<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // flatten bullets stored as [{text:...}, ...] -> [ '...' ]
        $flatten = function ($arr) {
            if (!$arr) return [];
            return collect($arr)->map(function($item){
                if (is_array($item) && isset($item['text'])) return $item['text'];
                return $item;
            })->values()->all();
        };

        $flattenClasses = function ($arr) {
            if (!$arr) return [];
            return collect($arr)->map(function($item){
                return [
                    'title' => $item['title'] ?? null,
                    'date' => $item['date'] ?? null,
                    'start_time' => $item['start_time'] ?? null,
                    'end_time' => $item['end_time'] ?? null,
                ];
            })->values()->all();
        };

        return [
            'slug' => $this->slug,
            'section1' => [
                'image' => $this->section1_image ? asset('storage/' . $this->section1_image) : null,
                'title' => $this->section1_title,
                'bullets' => $flatten($this->section1_bullets),
                'regular_price' => $this->section1_regular_price,
                'offer_price' => $this->section1_offer_price,
                'registration_text' => $this->section1_registration_text,
            ],
            'section2' => [
                'description' => $this->section2_description, // HTML supported
                'class_details' => $flattenClasses($this->section2_class_details),
            ],
            'section3' => [
                'submit_text' => $this->section3_submit_text,
            ],
            'section4' => [
                'terms_title' => $this->section4_terms_title,
                'terms_bullets' => $flatten($this->section4_terms_bullets),
            ],
        ];
    }
}
