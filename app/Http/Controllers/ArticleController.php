<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Request;

class ArticleController extends Controller
{
    public function index()
    {
        /* @var Builder $query */
        $query = Article::published()->orderBy('published_at', 'desc');

        // Narrow articles by tags form url query
        if ($queryTag = Request::query('tag')) {
            foreach (explode('~', $queryTag) as $filterTag) {
                $query->whereHas('tags', function (Builder $query) use ($filterTag) {
                    $query->where('name', $filterTag);
                });
            }

            $title = "Tags: " . str_replace('~', ', ', $queryTag);
        } else {
            $title = "All Articles";
        }

        $articles = $query->get();

        SEOMeta::setTitle($title)
            ->setDescription('Checkout out our awesome articles. We wrote them with soul!')
            ->setKeywords(Tag::pluck('name')->toArray());

        if ($articles->isNotEmpty()) {
            OpenGraph::addImage(asset($articles->first()->featured_image->xl()));
        }

        return view('article.index')->with([
            'articles' => $articles
        ]);
    }

    public function view($slug)
    {
        $query = Article::where('slug', $slug);

        // If admin is logged in, allow view of all articles,
        // not only published ones
        if (!Auth::check()) {
            $query->published();
        }

        /* @var Article $article */
        $article = $query->firstOrFail();

        // Featured articles (exclude current)
        $featuredArticles = Article::where('slug', '!=', $slug)
            ->published()
            ->featured()->inRandomOrder()
            ->limit(3)->get();

        SEOMeta::setTitle($article->title)
            ->setDescription($article->summary)
            ->setKeywords($article->tags->pluck('name')->toArray())
            ->setCanonical($article->getUrl());

        OpenGraph::addImage(asset($article->featured_image->xl()));

        return view('article.view')->with([
            'article' => $article,
            'featuredArticles' => $featuredArticles,
            'allTags' => Tag::all()
        ]);
    }
}
