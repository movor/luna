@extends('layouts.default')

@section('content')

    <div class="container blog-post-index">
        <div class="row">
            <div class="col-md-12">

                @if ($tags)

                    <h1 class="mb-4">
                        Blog Posts
                        <span class="text-muted">Containing Tags: {{ implode(', ', $tags) }}</span>
                    </h1>

                @else

                    <h1 class="mb-4">All Blog Posts</h1>

                @endif

                <div class="row">

                    @foreach($posts as $post)

                        <div class="col-lg-6">
                            <div class="card mb-4">

                                {{-- Inner card --}}

                                <div class="card card-inner text-white">
                                    <img class="card-img-top" src="{{ asset($post->featured_image->original()) }}">
                                    <div class="lead card-img-overlay d-flex align-items-end justify-content-end">

                                        @foreach($post->tags as $tag)

                                            <a href="{{ url("blog?tags=$tag->slug") }}"
                                               class="badge badge-primary ml-1"
                                            >
                                                {{ $tag->name }}
                                            </a>

                                        @endforeach

                                    </div>
                                </div>

                                {{-- /Inner card --}}

                                <div class="card-body">
                                    <h2 class="card-title text-truncate-2">{{ $post->title }}</h2>
                                    <p class="card-text text-justify text-truncate-3">
                                        {{ $post->summary }}
                                    </p>
                                    <a href="{{ $post->getPageUrl() }}" class="btn btn-primary">Read More</a>
                                </div>
                                <div class="card-footer text-muted">
                                    <a href="/about">{{ $post->user->name }}</a>
                                    <span class="float-right">
                                        <small class="align-text-bottom">
                                            {{ $post->published_at->format('d M Y') }}
                                        </small>
                                    </span>
                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>
            </div>
        </div>
    </div>

@endsection


