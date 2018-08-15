<?php

namespace App\Models\CustomCasts;

use App\Lib\ImageVariations\ImageVariations;

class ArticleFeaturedImageCast extends ImageCastBase
{
    public static function storageDir()
    {
        return 'uploads/article';
    }

    public function castAttribute($value)
    {
        return new ImageVariations($value ?? 'uploads/placeholders/placeholder.png');
    }
}