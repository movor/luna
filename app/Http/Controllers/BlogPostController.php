<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

use App\Models\BlogPost;
use App\Models\Traits\SeoTrait;
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

        // Descriptions doesn't work under Open-Graph and SEOmeta Fascades, some kind of error

        SEOMeta::setTitle(env('APP_NAME') . ' - Blog');
        SEOMeta::setDescription('A plethora of blog posts about web-development');
        SEOMeta::setCanonical(url('/blogs'));

        OpenGraph::setUrl(url('/log'));
        OpenGraph::setTitle(env('APP_NAME') . ' - Blog');
        OpenGraph::addProperty('type', 'articles');

        TwitterCard::setTitle('Blog @_movor');
        TwitterCard::setSite('@_movor');

        return view('blog_post.index', ['posts' => $posts, 'tags' => $tags]);
    }

    public function view($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();
        SEOMeta::setTitle($post->title);
        SEOMeta::setDescription($post->summary);
        SEOMeta::setDescription('lol');
        SEOMeta::addMeta('article:published_time', $post->published_at->toW3CString(), 'property');

        OpenGraph::setTitle($post->title);
        OpenGraph::setDescription($post->summary);
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::setUrl(url('/blog/') . $post->slug);
        OpenGraph::addProperty('type', 'article');
        OpenGraph::addProperty('locale', 'us-en');

        TwitterCard::setTitle('Blog @_movor');
        TwitterCard::setSite('@_movor');
        // TODO.SOLVE get single, first tag as a category
        SEOMeta::setCanonical(url('/blogs' . $post->getCanonicalUrl()));
        SEOMeta::addMeta('article:section', $post->getPrimaryTag()->name);
        // TODO.SOLVE set multiple tags as keywords
//      $this->seo()->metatags()->addKeyword(implode(",", $post->tags));


        // TODO.SOLVE: Implement cover url linked with every blog post

        // TODO.SOLVE: Check does everyblog has it's own cover url and bind it to element
        // OpenGraph::addImage($post->cover->url);
        // TODO.SOLVE: Learn more about open graph

        OpenGraph::addImage('http://image.url.com/cover.jpg', ['height' => 300, 'width' => 300]);

        return view('blog_post.view', ['post' => $post]);
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
