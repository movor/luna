<?php

namespace Movor\LaravelDbRedirect\Models;

use Illuminate\Database\Eloquent\Model;
use Movor\LaravelDbRedirect\DbRedirectHandler;
use Ramsey\Uuid\Uuid;

class Redirect extends Model
{
    public $incrementing = false;

    protected $casts = [
        'last_redirect_at' => 'datetime',
        'data' => 'array',
    ];

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Redirect $redirect) {
            // Insert uuid on creating
            if (!isset($redirect->id)) {
                $redirect->id = Uuid::uuid4()->toString();
            }
        });
    }

    /**
     * Setter for the "to" attribute
     *
     * @param $value
     *
     * @throws \Exception
     */
    public function setFromAttribute($value)
    {
        // Make sure "from" attribute is always the same through the package
        $this->attributes['from'] = (new DbRedirectHandler)->formatUri($value);
    }

    /**
     * Setter for the "to" attribute
     *
     * @param $value
     *
     * @throws \Exception
     */
    public function setToAttribute($value)
    {
        // Make sure "to" attribute is always the same through the package
        $this->attributes['to'] = (new DbRedirectHandler)->formatUri($value);
    }

    /**
     * Setter for the "status" attribute
     *
     * @param $value
     *
     * @throws \Exception
     */
    public function setStatusAttribute($value)
    {
        // Make sure status code is valid HTTP redirect code
        if (!in_array($value, range(300, 308))) {
            throw new \Exception('Invalid redirect status code: ' . $value);
        }

        $this->attributes['status'] = $value;
    }
}