<?php

namespace App\Lib\ImageVariations;

abstract class ImageVariationsBase
{
    /**
     * Path to image relative to /storage/app (path + file name + extension)
     *
     * @var string
     */
    protected $relPath;

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

    abstract public static function getSizes();

    public function __construct($imagePath)
    {
        $this->relPath = $imagePath;

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
        $imageSizes = static::getSizes();

        // Check for corresponding image size variable
        if (!array_key_exists($name, $imageSizes)) {
            throw new \Exception("There is no image size definition: $name");
        }

        $size = $imageSizes[$name];

        return $this->dir . '/' . $this->filename . '-' . $size . '.' . $this->extension;
    }

    /**
     * Get the uploaded version of image,
     * before resize (source version)
     *
     * @return string
     */
    public function source()
    {
        return $this->relPath;
    }

    /**
     * In case something someone tries to echo this object,
     * return source image path
     *
     * @return string
     */
    public function __toString()
    {
        return $this->relPath;
    }
}