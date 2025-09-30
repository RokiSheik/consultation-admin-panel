<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LandingPageResource;
use App\Models\LandingPage;


class LandingPageController extends Controller
{
    public function show($slug)
    {
        $page = LandingPage::where('slug', $slug)->firstOrFail();
        return new LandingPageResource($page);
    }
}
