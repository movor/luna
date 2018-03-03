<?php

namespace App\Models\CustomCasts;

use App\Lib\CustomCasts\CustomCastableBase;
use File;
use Image;

abstract class ImageCastBase extends CustomCastableBase
{
    /**
     * Callback
     *
     * @var
     */
    protected $storeImagesCallback;

    /**
     * Storage dir (relative to app/storage)
     *
     * @return mixed
     */
    abstract function storageDir();

    /**
     * @param $value
     *
     * @return string String to be inserted into database
     *
     * @throws \Exception
     */
    public function setAttribute($value)
    {
        // New value to be saved in corresponding db field
        $newValue = null;

        // Handle base64 image string (uploading via Backpack)
        if (starts_with($value, 'data:image')) {
            $extension = $this->getBase64fileExtension($value);
            $filename = str_random(16);

            // Make image object from base64 string
            $image = Image::make($value);
            $newValue = $this->storageDir() . '/' . $filename . '.' . $extension;

            // Make sure images are saved when after model is saved,
            // not here (when attribute is set)
            $this->storeImagesCallback = function () use ($image, $filename, $extension, $newValue) {
                $imageQuality = 75;
                $originalImage = clone $image;

                // Store original
                $image->save($newValue, $imageQuality);

                // Store other image sizes
                foreach (config('custom_castable.image_sizes') as $imageSize) {
                    list($width, $height) = explode('x', $imageSize);
                    $absolutePah = $this->storageDir() . '/' . $filename . '-' . $imageSize . '.' . $extension;

                    // Save image with 75% quality
                    (clone $originalImage)->fit($width, $height)
                        ->save($absolutePah, $imageQuality);
                }
            };
        } // Handle other types
        else {
            $newValue = $value;
        }

        return $newValue;
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

    /**
     * Created event
     *
     * Handle initial image saving
     */
    public function created()
    {
        ($this->storeImagesCallback)();
    }

    /**
     * Updating event
     *
     * Handle image replacing here, because we can check if corresonding
     * model field is dirty and update accordingly
     */
    public function updating()
    {
        // Check if image field is changed and if callback is defined
        if (is_callable($this->storeImagesCallback) && $this->model->isDirty($this->attribute)) {
            // Delete old images when updating model
            $this->deleteImages();

            // Callback to save images on model updating
            ($this->storeImagesCallback)();
        }
    }

    /**
     * Deleted event
     *
     * Handle image deleting
     */
    public function deleted()
    {
        // Delete related images when model is deleted
        $this->deleteImages();
    }

    /**
     * Delete original images as well as other variations (dimensions)
     */
    protected function deleteImages()
    {
        $pathInfo = pathinfo($this->model->getOriginal($this->attribute));
        $dir = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];

        $pattern = storage_path('app/' . $dir) . '/' . $filename . '*';

        File::delete(File::glob($pattern));
    }
}