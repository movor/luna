<?php

namespace App\Lib;

use App\Models\HashRecord;

// In order to use this trait your model should have
// "random_string" field in his table, and it's type should be
// "varchar" of 16 characters

trait CanonicalTrait
{
    // Running after model with this trait is successfully created
    public static function bootCanonicalTrait()
    {
        static::created(function ($model) {
            HashRecord::create([
                'hash' => str_random(),
                'model' => get_class($model),
                'record_id' => $model->id
            ]);
        });
    }

    public function getCanonicalUrl()
    {
        return url('canonical/' . $this->getHash());
    }

    public function getHash()
    {
        return HashRecord::where('model', get_class($this))
            ->where('record_id', $this->id)
            ->first()
            ->hash;
    }

}