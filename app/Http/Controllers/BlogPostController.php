<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogPostController extends Controller
{
    public function index()
    {
        // TODO: fail please
        $blogPosts = BlogPost::whereNotNull('published_at')->get();

        return view('blog_post.index', ['posts' => $blogPosts]);
    }

    public function show($slug)
    {
        $blogPost = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('blog_post.view', ['post' => $blogPost]);
    }

    public function showCanonical($id)
    {
        $blogPost = BlogPost::where('id', $id)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $blogPost->getCanonicalUrl();
        return view('blog_post.view', ['post' => $blogPost]);
    }
}
