<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Request;

class BlogPostController extends Controller
{
    public function index()
    {
        // TODO.SOLVE: fix this
        $tags = explode(',', Request::query('tags'));
        if (!empty($tags[0])) {
            $posts = BlogPost::whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('slug', explode(',', Request::query('tags')), 'and');
            })->whereNotNull('published_at')->get();
        } else {
            $posts = BlogPost::whereNotNull('published_at')->get();
        }

        $title = 'Blog Posts';
        $description = 'Checkout out our cool blog posts';

        SEOMeta::setTitle($title . ' | ' . env('APP_NAME'))->setDescription($description);
        OpenGraph::setDescription($description);

        return view('blog_post.index')->with([
            'posts' => $posts,
            'tags' => $tags
        ]);
    }

    public function view($slug)
    {
        /* @var BlogPost $post */
        $post = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $title = $post->title;
        $description = $post->summary;
        $keywords = $post->tags->pluck('name')->toArray();
        $image = $post->featured_image;

        SEOMeta::setTitle($title . ' | ' . env('APP_NAME'))
            ->setDescription($description)
            ->setKeywords($keywords);

        OpenGraph::addImage($image);

        return view('blog_post.view')->with([
            'post' => $post
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
