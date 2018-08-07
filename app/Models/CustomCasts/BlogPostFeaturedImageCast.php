<?php

namespace App\Models\CustomCasts;

class BlogPostFeaturedImageCast extends ImageCastBase
{
    public static function storageDir()
    {
        return 'uploads/blog_post';
    }

    public function castAttribute($value)
    {
        return new ImageVariations($value ?? 'uploads/placeholders/placeholder.png');
    }
}