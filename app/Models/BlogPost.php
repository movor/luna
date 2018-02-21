<?php

namespace App\Models;

use App\Models\Traits\CanonicalUrlTrait;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Parsedown;

class BlogPost extends Model
{
    use CrudTrait, CanonicalUrlTrait;

    protected $fillable = ['user_id', 'blog_tag_id', 'title', 'summary', 'body', 'slug', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime'
    ];

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

    public function primaryTag()
    {
        return $this->belongsTo(BlogTag::class);
    }

    public function getBodyHtmlAttribute()
    {
        $Parsedown = new Parsedown();

        return $Parsedown->text($this->body);
    }

    public function getIsPublishedAttribute()
    {
        return $this->isPublished();
    }

    public function isPublished()
    {
        return (bool)$this->published_at;
    }
}
