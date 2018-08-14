<?php

namespace App\Models;

use App\Models\CustomCasts\BlogPostFeaturedImageCast;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Movor\LaravelCustomCasts\CustomCastableTrait;
use Movor\LaravelDbRedirector\Models\RedirectRule;
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
        'featured_image' => BlogPostFeaturedImageCast::class,
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'commentable' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        // Create 301 redirect when slug changes
        static::updated(function (BlogPost $blogPost) {
            if ($blogPost->isDirty('slug')) {
                RedirectRule::create([
                    'origin' => 'blog/' . $blogPost->getOriginal('slug'),
                    'destination' => 'blog/' . $blogPost->slug
                ]);
            }
        });

        // Remove redirects when post is deleted
        static::deleted(function (BlogPost $blogPost) {
            try {
                RedirectRule::deleteChainedRecursively('blog/' . $blogPost->slug);
            } catch (\Exception $e) {
            }
        });
    }

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

    public function getUrl()
    {
        return url('blog/' . $this->slug);
    }
}
