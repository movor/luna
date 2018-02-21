<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    use CrudTrait;

    protected $fillable = ['name'];

    /**
     * @return BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(BlogPost::class);
    }

    // TODO.AFTER:sjzcpLBf
    // Remove this method because slug will be existing db field
    public function getSlugAttribute()
    {
        return str_slug($this->name);
    }
}
