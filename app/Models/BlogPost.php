<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogPost extends Model
{
    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class);
    }
}