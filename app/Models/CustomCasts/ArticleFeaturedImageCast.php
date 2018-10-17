<?php

namespace App\Models\CustomCasts;

use App\Lib\ImageVariations\ImageVariations_16_9;

class ArticleFeaturedImageCast extends ImageCastBase
{
    public static function storageDir()
    {
        return 'uploads/article';
    }

    public static function imageSizes()
    {
        return ImageVariations_16_9::getSizes();
    }

    public function castAttribute($value)
    {
        return new ImageVariations_16_9($value ?? 'uploads/placeholders/placeholder.png');
    }
}