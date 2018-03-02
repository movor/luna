<?php

namespace App\Models;

use App\Lib\CustomCasts\CustomCastableTrait;
use App\Models\CustomCasts\BlogPostFeaturedImageCast;
use App\Models\Traits\PageUrl;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Parsedown;

class BlogPost extends Model
{
    use CrudTrait, PageUrl, CustomCastableTrait;

    protected $fillable = ['user_id', 'title', 'summary', 'body', 'slug', 'featured_image', 'published_at', 'featured'];

    protected $baseUrl = 'blog';

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'featured_image' => BlogPostFeaturedImageCast::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class)->orderBy('primary', 'desc')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsPublishedAttribute()
    {
        return (bool) $this->published_at;
    }

    public function getPrimaryTag()
    {
        return $this->tags()->where('primary', true)->first();
    }

    public function scopeFeatured($query, $featured = true)
    {
        return $query->where('featured', $featured);
    }

    public function scopePublished($query, $published = true)
    {
        return $published
            ? $query->whereNotNull('published_at')
            : $query->whereNull('published_at');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords($value);
    }

    public function getBodyHtmlAttribute()
    {
        return (new Parsedown)->text($this->body);
    }
}
