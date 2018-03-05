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
        return $value
            ? new ImageVariations($value)
            // ImageVariation object for placeholder
            : new ImageVariationsPlaceholder('img/placeholders/blog_post.jpg');
    }
}