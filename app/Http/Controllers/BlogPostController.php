<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::whereNotNull('published_at')->get();

        SEOMeta::setTitle(env('APP_NAME') . " - Blog");
        SEOMeta::setDescription("Writings 'bout different stuff related to Linux, PHP, Javascript and Blockchain technologies");
        SEOMeta::setCanonical(env('APP_URL') . "/blog/");

        // TODO.? Look for a difference between Description vs OpenGraph description
        OpenGraph::setDescription('Writings \'bout different stuff related to Linux, PHP, Javascript and Blockchain technologies');
        OpenGraph::setTitle(env('APP_NAME') . " - Blog");
        OpenGraph::setUrl(env('APP_URL') . "/blog/");
        OpenGraph::addProperty('type', 'articles');

        TwitterCard::setTitle('Blog @_movor');
        TwitterCard::setSite('@_movor');

        return view('blog_post.view', ['post' => $posts]);
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        SEOMeta::setTitle($post->title);
        SEOMeta::setDescription($post->summary);
        SEOMeta::addMeta('article:published_time', $post->published_at->toW3CString(), 'property');
        // TODO.SOLVE get single, first tag as a category
        SEOMeta::setCanonical(env('APP_NAME') . $post->getCanonicalUrl());
        // SEOMeta::addMeta('article:section', $post->tags->pluck('id', 'name')[1], 'property');
        // TODO.SOLVE set multiple tags as keywords
        // SEOMeta::addKeyword([$post->tags()]);

        OpenGraph::setDescription($post->summary);
        OpenGraph::setTitle($post->title);
        OpenGraph::setUrl(env('APP_URL') . '/blog/' . $post->slug);
        OpenGraph::addProperty('type', 'article');
        OpenGraph::addProperty('locale', 'us-en');

        // TODO.SOLVE: Implement cover url linked with every blog post
        // SOLVEOpenGraph::addImage($post->cover->url);
        // OpenGraph::addImage($post->images->list('url'));
        OpenGraph::addImage(['url' => 'http://image.url.com/cover.jpg', 'size' => 300]);
        OpenGraph::addImage('http://image.url.com/cover.jpg', ['height' => 300, 'width' => 300]);

        return view('blog_post.view', ['post' => $post]);
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
