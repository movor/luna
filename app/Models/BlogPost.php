<?php

namespace App\Models;

use App\Lib\CustomCasts\CustomCastableTrait;
use App\Models\CustomCasts\ImageCast;
use App\Models\Traits\PageUrl;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Parsedown;

class BlogPost extends Model
{
    use CrudTrait, PageUrl, CustomCastableTrait;

    protected $fillable = ['user_id', 'title', 'summary', 'body', 'slug', 'featured_image', 'published_at'];

    protected $baseUrl = 'blog';

    protected $casts = [
        'published_at' => 'datetime',
        'featured_image' => ImageCast::class
    ];

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute()
    {
        return (new Parsedown)->text($this->body);
    }

    public function getIsPublishedAttribute()
    {
        return $this->isPublished();
    }

    public function isPublished()
    {
        return (bool) $this->published_at;
    }

    public function getPrimaryTag(): BlogTag
    {
        return $this->tags()->where('primary', true)->first();
    }
}