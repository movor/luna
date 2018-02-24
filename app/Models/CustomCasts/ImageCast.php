<?php

namespace App\Models\CustomCasts;

class ImageCast extends ImageCastBase
{
    protected $storageDir = 'uploads';

    public function castAttribute($value)
    {
        return $value ?: '/img/remote/' . $this->model->slug . '.jpg';
    }
}