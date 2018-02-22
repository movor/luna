<?php

namespace App\Models\CustomCasts;

use Image;
use Storage;

abstract class ImageCastBase extends FileCastBase
{
    public function castAttribute($value)
    {
        return $value ?: 'img/image_placeholder.png';
    }

    public function setAttribute($value)
    {
        $newValue = null;

        // Handle base64 image string
        if (starts_with($value, 'data:image')) {
            $extension = $this->getBase64fileExtension($value);
            $filename = str_random(40) . '.' . $extension;

            // Make image object from base64 string
            $image = Image::make($value);
            $newValue = $this->storageDir . '/' . $filename;

            // Make sure image is saved when model is saved,
            // not here (when attribute is set)
            $this->callback = function () use ($newValue, $image) {
                Storage::put($newValue, $image->stream());
            };
        } // Handle other types
        else {
            $newValue = parent::setAttribute($value);
        }

        return $newValue ?: $value;
    }

    /**
     * Get extension from base64 string
     *
     * @param $base64string
     *
     * @throws \Exception
     *
     * @return string
     */
    private function getBase64fileExtension($base64string)
    {
        $start = strpos($base64string, '/') + 1;
        $end = strpos($base64string, ';');

        if ($start === false || $end === false) {
            throw new \Exception('Can`t get extension from base64 encoded string');
        }

        return substr($base64string, $start, $end - $start);
    }
}