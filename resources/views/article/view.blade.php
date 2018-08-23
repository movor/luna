@extends('layouts.default')

@section('content')

    <div class="container article-view">
        <div class="row">
            <div class="col-lg-9">
                <h1 class="mb-4">{{ $article->title }}</h1>
                <div class="mb-3">
                    <div>
                        Author:
                        <a class="h5" href="{{ url('about') }}">{{ $article->user->name }}</a>
                    </div>
                    <div class="mb-3">
                        Posted on
                        {{ $article->published_at ? $article->published_at->format('d F, Y') : '' }}
                    </div>
                    <div>
                        Tags:

                        @php($primaryTag = $article->getPrimaryTag())

                        @foreach($article->tags as $tag)

                            <a href="{{ url("article?tag=$tag->name") }}"
                               class="badge {{ $tag->id == optional($primaryTag)->id ? 'badge-danger' : 'badge-primary' }}"
                            >
                                {{ $tag->name }}
                            </a>

                        @endforeach

                    </div>
                </div>

                {{-- Summary and body --}}

                <div class="">{{ $article->summary }}</div>

                <hr class="my-4">

                <div class="post-body text-justify">{!! $article->body_html !!}</div>

                {{-- /Summary and body --}}

            </div>
            <div class="col-lg-3 col-md-12">

                {{-- All Tags --}}

                @if($allTags->isNotEmpty())

                    <h5 class="text-muted">All Tags</h5>

                    <div class="card mb-4">

                        <div class="card-body">

                            @foreach($allTags as $tag)

                                <a class="badge badge-secondary mr-1 mb-1" href="{{ url('article?tag=' . $tag->name) }}">{{ $tag->name }}</a>

                            @endforeach

                        </div>
                    </div>

                @endif

                {{-- /All Tags --}}

                {{-- Featured Articles --}}

                @if($featuredArticles->isNotEmpty())

                    <h5 class="text-muted">Featured Articles</h5>

                    <div class="row featured-articles">

                        @foreach($featuredArticles as $featuredArticle)

                            <div class="col-sm-6 col-md-4 col-lg-12">
                                <a href="{{ url('article/' . $featuredArticle->slug) }}">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <p class="card-title font-weight-bold mb-0 text-muted">{{ $featuredArticle->title }}</p>
                                        </div>

                                        <div class="card-footer">

                                            @php($primaryTag = $featuredArticle->getPrimaryTag())

                                            @foreach($featuredArticle->tags as $tag)

                                                <a href="{{ url("article?tag=$tag->name") }}"
                                                   class="badge {{ $tag->id == optional($primaryTag)->id ? 'badge-danger' : 'badge-primary' }}"
                                                >
                                                    {{ $tag->name }}
                                                </a>

                                            @endforeach

                                        </div>
                                    </div>
                                </a>
                            </div>

                        @endforeach

                    </div>

                @endif

                {{-- /Featured Articles --}}

            </div>
        </div>
    </div>

@endsection
