@extends('layouts.default')

@section('content')

    <div class="container blog-post-view">
        <div class="row">
            <div class="col-lg-9">
                <h1 class="mb-4">{{ $post->title }}</h1>
                <div class="lead mb-3">
                    <div>
                        <span class="text-muted">Author:</span>
                        <strong><a class="bold" href="{{ url('about') }}">{{ $post->user->name }}</a></strong>
                    </div>
                    <div class="mb-3">
                        <small>
                            <span class="text-muted">Posted on</span>
                            {{ $post->published_at ? $post->published_at->format('d F, Y') : '' }}
                        </small>
                    </div>
                    <div>
                        <small class="text-muted">Tags:</small>

                        @foreach($post->tags as $tag)

                            <a href="{{ url("blog?tags=$tag->name") }}" class="badge badge-primary">{{ $tag->name }}</a>

                        @endforeach

                    </div>
                </div>

                {{-- Image, summary and body --}}

                <img class="img-fluid rounded" src="{{ asset($post->featured_image->xl()) }}" alt="">

                <div class="alert alert-lite">{{ $post->summary }}</div>

                <div class="post-body text-justify">{!! $post->body_html !!}</div>

                {{-- /Image, summary and body --}}

            </div>
            <div class="col-lg-3 col-md-12">

                {{-- All Tags --}}

                @if($allTags->isNotEmpty())

                    <h5 class="text-muted">All Tags</h5>

                    <div class="card mb-4">

                        <div class="card-body">

                            @foreach($allTags as $tag)

                                <a class="badge badge-primary mr-1 mb-1" href="/blog?tags={{ $tag->name }}">{{ $tag->name }}</a>

                            @endforeach

                        </div>
                    </div>

                @endif

                {{-- /All Tags --}}

                {{-- Featured Posts --}}

                @if($featuredPosts->isNotEmpty())

                    <h5 class="text-muted">Featured Posts</h5>

                    <div class="row featured-posts">

                        @foreach($featuredPosts as $featuredPost)

                            <div class="col-sm-6 col-md-4 col-lg-12">
                                <a href="{{ url('blog/' . $featuredPost->slug) }}">
                                    <div class="card mb-4">
                                        <img class="card-img-top" src="{{ asset($featuredPost->featured_image->lg()) }}">
                                        <div class="card-body">
                                            <p class="card-title font-weight-bold mb-0 text-muted">{{ $featuredPost->title }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        @endforeach

                    </div>

                @endif

                {{-- /Featured Posts --}}

            </div>
        </div>
    </div>

    <div class="container">
        <hr class="mt-5">

        {{-- Load Disqs Vue component if post is commentable --}}
        @if($post->commentable)

            <app-disqus
                    website="{{ env('DISQS_WEBSITE') }}"
                    title="{{ env('APP_NAME') }}"
                    identifier="{{ '/blog-post/' . $post->id }}"
                    url="{{ url('/blog-post/' . $post->id) }}"
            ></app-disqus>

        @endif

    </div>

@endsection
