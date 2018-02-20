<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\User;

class BlogPostController extends Controller
{
    public function index()
    {
        // TODO: fail please
        $blogPosts = BlogPost::whereNotNull('published_at')->get();

        return view('posts', ['posts' => $blogPosts]);
    }

    public function show($slug)
    {
        $blogPost = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('post', ['post' => $blogPost]);

    }

    public function showCanonical($id)
    {

        $blogPost = BlogPost::where('id', $id)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $blogPost->getCanonicalUrl();
        return view('post', ['post' => $blogPost]);
    }
}
