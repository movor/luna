<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
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
        $query = Article::published()->orderBy('published_at', 'desc');

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

        SEOMeta::setTitle($title)
            ->setDescription('Checkout out our awesome blog posts. We wrote them with soul!')
            ->setKeywords(Tag::pluck('name')->toArray())
            ->setCanonical(url('blog'));

        if ($posts->isNotEmpty()) {
            OpenGraph::addImage(asset($posts->first()->featured_image->xl()));
        }

        return view('blog_post.index')->with([
            'title' => $title,
            'posts' => $query->get()
        ]);
    }

    public function view($slug)
    {
        $query = Article::where('slug', $slug);

        // If admin is logged in, allow view of all posts,
        // not only published ones
        if (!Auth::check()) {
            $query->published();
        }

        /* @var Article $post */
        $post = $query->firstOrFail();

        // Featured posts (exclude current)
        $featuredPosts = Article::where('slug', '!=', $slug)
            ->published()
            ->featured()->inRandomOrder()
            ->limit(3)->get();

        $this->setMeta($post);

        return view('blog_post.view')->with([
            'post' => $post,
            'featuredPosts' => $featuredPosts,
            'allTags' => Tag::all()
        ]);
    }

    protected function setMeta(Article $post)
    {
        SEOMeta::setTitle($post->title)
            ->setDescription($post->summary)
            ->setKeywords($post->tags->pluck('name')->toArray())
            ->setCanonical($post->getUrl());

        OpenGraph::addImage(asset($post->featured_image->xl()));
    }
}
