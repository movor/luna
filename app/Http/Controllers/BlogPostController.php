<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Request;

class BlogPostController extends Controller
{
    public function index()
    {
        /* @var Builder $query */
        $query = BlogPost::published();

        $tags = Request::query('tags');

        if ($tags) {
            $tags = explode(',', $tags);
            $query->whereHas('tags', function (Builder $query) use ($tags) {
                $query->whereIn('slug', explode(',', Request::query('tags')), 'and');
            });
        }

        // SEO
        $title = 'Blog Posts';
        $description = 'Checkout out our cool blog posts. We are really proud of them.';
        SEOMeta::setTitle($title)->setDescription($description);

        return view('blog_post.index')->with([
            'posts' => $query->get(),
            'tags' => $tags
        ]);
    }

    public function view($slug)
    {
        $query = BlogPost::where('slug', $slug);

        // If admin is logged in, allow view of all posts,
        // not only published ones
        if (!Auth::check()) {
            $query->published();
        }

        /* @var BlogPost $post */
        $post = $query->firstOrFail();

        // Featured posts
        $featuredPosts = BlogPost::where('slug', '!=', $slug)
            ->published()
            ->featured()->inRandomOrder()
            ->limit(3)->get();

        // SEO
        $title = $post->title;
        $description = $post->summary;
        $keywords = $post->tags->pluck('name')->toArray();
        $image = $post->featured_image->source();
        SEOMeta::setTitle($title)
            ->setDescription($description)
            ->setKeywords($keywords);
        OpenGraph::addImage($image);

        return view('blog_post.view')->with([
            'post' => $post,
            'featuredPosts' => $featuredPosts
        ]);
    }

    public function viewCanonical($id)
    {
        // TODO: add SEO tools to the canonical page, currently default
        $blogPost = BlogPost::where('id', $id)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('blog_post.view', ['post' => $blogPost]);
    }
}
