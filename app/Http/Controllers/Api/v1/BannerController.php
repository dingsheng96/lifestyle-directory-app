<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\BannerRequest;

class BannerController extends Controller
{
    public function show(BannerRequest $request)
    {
        $banner = Banner::where('id', $request->get('banner_id'))->publish()->firstOrFail();

        return view('webview.banner', compact('banner'));
    }
}
