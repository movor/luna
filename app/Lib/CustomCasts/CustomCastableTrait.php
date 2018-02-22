<?php

namespace App\Lib\CustomCasts;

use Illuminate\Database\Eloquent\Model;

trait CustomCastableTrait
{
    /**
     * Each field which is going to be custom casted
     * will have its own custom cast object
     *
     * @var CustomCastableBase[]
     */
    protected $customCastInstances = [];

    public static function bootCustomCastableTrait()
    {
        // Handle event firing in custom cast classes
        \Event::listen('eloquent.*: ' . get_called_class(), function ($event, $data) {
            $eventName = explode('.', explode(':', $event)[0])[1];

            /** @var Model $model */
            $model = $data[0];

            foreach ($model->filterCustomCasts() as $attribute => $customCastClass) {
                // Determine if event handler method is defined in
                // custom cast object
                if (method_exists($customCastClass, $eventName)) {
                    // Init custom cast object and call corresponding callback
                    $model->getCustomCastInstance($attribute)->$eventName();
                }
            }
        });
    }

    /**
     * Set attribute
     *
     * @param $attribute
     * @param $value
     *
     * @return mixed
     */
    public function setAttribute($attribute, $value)
    {
        // Handle defined mutators in object and
        // prioritize them against custom castable
        if ($this->hasSetMutator($attribute)) {
            $method = 'set' . studly_case($attribute) . 'Attribute';

            return $this->{$method}($value);
        }

        // Skip all attributes that has no custom cast class
        if ($this->getCustomCast($attribute)) {
            $customCastObject = $this->getCustomCastInstance($attribute);
            $this->attributes[$attribute] = $customCastObject->setAttribute($value);

            return $this;
        }

        // Fallback to default behavior
        return parent::setAttribute($attribute, $value);
    }

    /**
     * Cast attribute (from db value to our custom format)
     *
     * @param $attribute
     * @param $value
     *
     * @return mixed|null
     */
    protected function castAttribute($attribute, $value)
    {
        // Skip non custom cast attributes
        if (!$this->getCustomCast($attribute)) {
            // Fallback to default behavior
            return parent::castAttribute($attribute, $value);
        }

        return $this->getCustomCastInstance($attribute)->castAttribute($value);
    }

    /**
     * Filter custom casts out of all casts
     *
     * @return array Key: attribute, Value: custom cast class
     */
    private function filterCustomCasts()
    {
        $customCasts = [];
        foreach ($this->casts as $attribute => $castType) {
            if (is_subclass_of($castType, CustomCastableBase::class)) {
                $customCasts[$attribute] = $castType;
            }
        }

        return $customCasts;
    }

    /**
     * Get custom cast class
     *
     * @param $attribute
     *
     * @return string|null Full c
     */
    private function getCustomCast($attribute)
    {
        $customCasts = $this->filterCustomCasts();

        return array_get($customCasts, $attribute);
    }

    /**
     * Lazy load custom cast object and return it
     *
     * @param $attribute
     *
     * @return CustomCastableBase
     */
    private function getCustomCastInstance($attribute)
    {
        if (!isset($this->customCastInstances[$attribute])) {
            $customCast = $this->getCustomCast($attribute);

            if ($customCast) {
                $customCastObject = new $customCast($this, $attribute);
                $this->customCastInstances[$attribute] = $customCastObject;
            }
        }

        return $this->customCastInstances[$attribute];
    }
}