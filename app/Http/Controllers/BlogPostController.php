<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogTag;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Auth;
use Illuminate\Database\Eloquent\Builder;
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

            $title = 'Blog Posts Containing Tags: ' . implode(', ', $tags);
        } else {
            $title = "All Blog Posts";
        }

        $posts = $query->get();

        // SEO
        SEOMeta::setTitle($title)
            ->setDescription('Checkout out our awesome blog posts. We wrote them with soul!')
            ->setKeywords(BlogTag::pluck('name')->toArray())
            ->setCanonical(url('blog'));
        OpenGraph::addImage(asset($posts->first()->featured_image->xl()));

        return view('blog_post.index')->with([
            'title' => $title,
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

        // Featured posts (exclude current)
        $featuredPosts = BlogPost::where('slug', '!=', $slug)
            ->published()
            ->featured()->inRandomOrder()
            ->limit(3)->get();

        $this->setMeta($post);

        return view('blog_post.view')->with([
            'post' => $post,
            'featuredPosts' => $featuredPosts
        ]);
    }

    public function viewCanonical($id)
    {
        $post = BlogPost::published()
            ->where('id', $id)
            ->firstOrFail();

        // Featured posts (exclude current)
        $featuredPosts = BlogPost::where('id', '!=', $id)
            ->published()
            ->featured()->inRandomOrder()
            ->limit(3)->get();

        $this->setMeta($post);

        return view('blog_post.view')->with([
            'post' => $post,
            'featuredPosts' => $featuredPosts
        ]);
    }

    protected function setMeta(BlogPost $post)
    {
        SEOMeta::setTitle($post->title)
            ->setDescription($post->summary)
            ->setKeywords($post->tags->pluck('name')->toArray())
            ->setCanonical(url('blog-post/' . $post->id));

        OpenGraph::addImage(asset($post->featured_image->xl()));
    }
}
