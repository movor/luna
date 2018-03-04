<?php

namespace App\Models\CustomCasts;

class ImageVariationsPlaceholder extends ImageVariations
{
    /**
     * Intercept calls for different image dimensions
     * and return corresponding one (based on called method name)
     *
     * @param $name
     * @param $arguments
     *
     * @return string
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $imageSizes = config('custom_castable.image_sizes');

        // Check for corresponding image size variable
        if (!array_key_exists($name, $imageSizes)) {
            throw new \Exception("There is no image size definition: $name");
        }

        return $this->absolutePath;
    }
}