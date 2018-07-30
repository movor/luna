<?php

namespace App\Models;

use App\Lib\CustomCasts\CustomCastableTrait;
use App\Models\CustomCasts\BlogPostFeaturedImageCast;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Parsedown;

class BlogPost extends Model
{
    use CrudTrait, CustomCastableTrait;

    protected $fillable = [
        'user_id',
        'title',
        'summary',
        'body',
        'slug',
        'featured',
        'featured_image',
        'commentable',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'featured_image' => BlogPostFeaturedImageCast::class,
        'commentable' => 'boolean',
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

    public function getFeaturedImageRawAttribute()
    {
        return $this->getOriginal('featured_image');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = title_case($value);
    }

    public function getBodyHtmlAttribute()
    {
        $rendered = (new Parsedown)->text($this->body);

        $replace = [
            // Customizations (Bootstrap classes)
            '<table>' => '<table class="table">',
            // Resolve curly braces ("{{") Vue rendering
            '<code' => '<code v-pre',
            // Links always in new tab
            '<a href="' => '<a target="_blank" href="',
        ];

        return str_replace(array_keys($replace), $replace, $rendered);
    }

    public function getPageUrl()
    {
        return url('blog/' . $this->slug);
    }

    public function getPrimaryTag()
    {
        $tag = $this->tags()->where('primary', true)->first();

        return $tag ?: $this->tags()->first();
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
}
