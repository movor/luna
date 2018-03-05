<?php

namespace App\Models\CustomCasts;

class ImageVariationsPlaceholder extends ImageVariations
{
    public function __call($name, $arguments)
    {
        return $this->relPath;
    }
}