<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Request;

class BlogPostController extends Controller
{
    use SEOToolsTrait;

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

        $this->seo()->setTitle(env('APP_NAME') . ' - Blog');
        $this->seo()->setDescription('A plethora of blog posts about web-development');
        $this->seo()->setCanonical(url('/blogs'));

        $this->seo()->opengraph()->setUrl(url('/log'));
        $this->seo()->opengraph()->setTitle(env('APP_NAME') . ' - Blog');
        $this->seo()->opengraph()->addProperty('type', 'articles');

        $this->seo()->twitter()->setTitle('Blog @_movor');
        $this->seo()->twitter()->setSite('@_movor');

        return view('blog_post.index', ['posts' => $posts, 'tags' => $tags]);
    }

    public function view($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();
        $this->seo()->setTitle($post->title);
        $this->seo()->setDescription($post->summary);
        $this->seo()->metatags()->addMeta('article:published_time', $post->published_at->toW3CString(), 'property');

        $this->seo()->opengraph()->setTitle($post->title);
        $this->seo()->opengraph()->setDescription($post->summary);
        $this->seo()->opengraph()->addProperty('type', 'articles');
        $this->seo()->opengraph()->setUrl(url('/blog/') . $post->slug);
        $this->seo()->opengraph()->addProperty('type', 'article');
        $this->seo()->opengraph()->addProperty('locale', 'us-en');

        $this->seo()->twitter()->setTitle('Blog @_movor');
        $this->seo()->twitter()->setSite('@_movor');
        // TODO.SOLVE get single, first tag as a category
        $this->seo()->setCanonical(url('/blogs' . $post->getCanonicalUrl()));
        $this->seo()->metatags()->addMeta('article:section', $post->getPrimaryTag()->name);
        // TODO.SOLVE set multiple tags as keywords
//      $this->seo()->metatags()->addKeyword(implode(",", $post->tags));


        // TODO.SOLVE: Implement cover url linked with every blog post

        // TODO.SOLVE: Check does everyblog has it's own cover url and bind it to element
        // $this->seo()->opengraph()->addImage($post->cover->url);
        // TODO.SOLVE: Learn more about open graph
        $this->seo()->opengraph()->addImage('http://image.url.com/cover.jpg', ['height' => 300, 'width' => 300]);

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
