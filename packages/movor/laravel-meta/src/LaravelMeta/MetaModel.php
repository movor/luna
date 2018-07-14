<?php

namespace Movor\LaravelMeta;

use Illuminate\Database\Eloquent\Model;

class MetaModel extends Model
{
    public $casts = [
        'data' => 'array'
    ];

    protected $guarded = [];

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
}