<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogTag;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Request;

class BlogPostController extends Controller
{
    public function index()
    {
        /* @var Builder $query */
        $query = BlogPost::published()->orderBy('published_at', 'desc');

        $filterTags = Request::query('tags');

        if ($filterTags) {
            $filterTags = explode(',', $filterTags);
            $query->whereHas('tags', function (Builder $query) use ($filterTags) {
                $query->whereIn('name', explode(',', Request::query('tags')), 'and');
            });

            $title = 'Blog Posts Containing Tags: ' . implode(', ', $filterTags);
        } else {
            $title = "All Blog Posts";
        }

        $posts = $query->get();

        return view('blog_post.index')->with([
            'title' => $title,
            'posts' => $query->get()
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

        return view('blog_post.view')->with([
            'post' => $post,
            'featuredPosts' => $featuredPosts,
            'allTags' => BlogTag::all()
        ]);
    }
}
