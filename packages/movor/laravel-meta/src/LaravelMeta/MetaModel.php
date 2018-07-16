<?php

namespace Movor\LaravelMeta;

use Illuminate\Database\Eloquent\Model;

class MetaModel extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::saving(function (Model $model) {
            if (is_null($model->realm)) {
                $model->realm = config('laravel-meta.default_realm');
            }
        });
    }

    /**
     * Set table name dynamically
     *
     * @return string
     */
    public function getTable()
    {
        return config('laravel-meta.table_name');
    }

    public function metable()
    {
        return $this->morphTo();
    }

    /**
     * Standard meta query builder
     *
     * @param        $query
     * @param null   $realm
     * @param string $metableType
     * @param string $metableId
     *
     * @return mixed
     */
    public function scopeFilter($query, $realm, $metableType, $metableId)
    {
        return $query->where([
            'realm' => !is_null($realm) ? $realm : config('laravel-meta.default_realm'),
            'metable_type' => $metableType,
            'metable_id' => $metableId,
        ]);
    }
}