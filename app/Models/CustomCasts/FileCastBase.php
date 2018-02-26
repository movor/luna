<?php

namespace App\Models\CustomCasts;

use App\Lib\CustomCasts\CustomCastableBase;
use Illuminate\Http\UploadedFile;
use Storage;

abstract class FileCastBase extends CustomCastableBase
{
    /**
     * Callback
     *
     * @var
     */
    protected $callback;

    /**
     * Storage dir (relative to app/storage)
     *
     * @return mixed
     */
    abstract function storageDir();

    public function setAttribute($value)
    {
        $newValue = null;

        if ($value instanceof UploadedFile) {
            $extension = $value->extension();
            $filename = str_random(40) . '.' . $extension;
            $newValue = $this->storageDir() . '/' . $filename;

            // Make sure image is saved when model is saved,
            // not when attribute is set
            $this->callback = function () use ($value, $filename) {
                $value->storeAs($this->storageDir(), $filename);
            };
        }

        return $newValue ?: $value;
    }

    /**
     * Saved event
     *
     * Handle image saving
     */
    public function saved()
    {
        // Check if modelSavedCallback is defined
        if (is_callable($this->callback)) {
            // Callback
            ($this->callback)();
        }
    }

    /**
     * Updating event
     *
     * Handle image replacing
     */
    public function updating()
    {
        // Check if image field is changed and if modelSavedCallback is defined
        if (is_callable($this->callback) && $this->model->isDirty($this->attribute)) {
            // Delete image (by retrieving old model value)
            Storage::delete($this->model->getOriginal($this->attribute));

            // Callback
            ($this->callback)();
        }
    }

    /**
     * Deleted event
     *
     * Handle image deleting
     */
    public function deleted()
    {
        $attribute = $this->attribute;
        $imagePath = $this->model->$attribute;
        Storage::delete($imagePath);
    }
}