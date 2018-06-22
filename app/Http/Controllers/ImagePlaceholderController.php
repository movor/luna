<?php

namespace App\Http\Controllers;

class ImagePlaceholderController extends Controller
{
    public function get($name)
    {
        $width = Request::query('width', 1280);
        $height = Request::query('height', 720);

        $cacheKey = 'placeholderImage.' . $name . '-' . $width . 'x' . $height;

        $image = \Cache::rememberForever($cacheKey, function () use ($width, $height) {
            return file_get_contents("https://picsum.photos/$width/$height?random");
        });

        return Image::make($image)->response();
    }
}