<?php

namespace App\Models\CustomCasts;

class ImageVariations
{
    /**
     * Full path to image (path + file name + extension)
     *
     * @var string
     */
    protected $absolutePath;

    /**
     * Image base name (no path, no extension)
     *
     * @var string
     */
    protected $filename;

    /**
     * Directory path (without file name, no trailing slash)
     *
     * @var string
     */
    protected $dir;

    /**
     * Extension (after last dot in name)
     *
     *
     * @var string
     */
    protected $extension;

    public function __construct($imagePath)
    {
        $this->absolutePath = $imagePath;

        $pathInfo = pathinfo($imagePath);
        $this->dir = $pathInfo['dirname'];
        $this->filename = $pathInfo['filename'];
        $this->extension = $pathInfo['extension']; // TODO.SOLVE if image name is ".some_file" or no extension at all
    }

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

        $size = $imageSizes[$name];

        return $this->dir . '/' . $this->filename . '-' . $size . '.' . $this->extension;
    }

    public function original()
    {
        return $this->absolutePath;
    }
}