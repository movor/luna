<?php

namespace App\Models\CustomCasts;

class BlogPostFeaturedImageCast extends ImageCastBase
{
    public function storageDir()
    {
        return 'uploads';
    }

    public function castAttribute($value)
    {
        return $value
            ? asset($value)
            : asset('img/placeholders/blog_post.jpg');
    }
}