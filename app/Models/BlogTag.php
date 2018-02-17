<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    /**
     * @return BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(BlogPost::class);
    }
}