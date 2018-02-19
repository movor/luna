<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogPost extends Model
{
    use CrudTrait;

    protected $fillable = ['user_id', 'title', 'summary', 'body'];

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}