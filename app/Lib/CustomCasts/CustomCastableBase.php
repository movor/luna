<?php

namespace App\Lib\CustomCasts;

use Illuminate\Database\Eloquent\Model;

abstract class CustomCastableBase
{
    /**
     * Model
     *
     * @var Model
     */
    protected $model;

    /**
     * Corresponding db field (model attribute name)
     *
     * @var string
     */
    protected $attribute;

    public function __construct(Model $model, $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function setAttribute($value);

    public function castAttribute($value)
    {
        return $value;
    }
}