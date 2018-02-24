@extends('layouts.default')

@section('content')

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="mb-4">{{ $post->title }}</h1>
                <div class="lead">
                    <div>Author: <a href="#">{{ $post->user->name }}</a></div>
                    <div>
                        <small>Posted on {{ $post->published_at->format('d M Y') }}</small>
                    </div>
                    <div>

                        @foreach($post->tags->pluck('name')->toArray() as $tag)

                            <a href="{{ url("blog?tags=$tag") }}" class="badge badge-primary">{{ $tag }}</a>

                        @endforeach

                    </div>
                </div>
                <img class="img-fluid rounded my-3" src="{{ asset($post->featured_image) }}" alt="">
                {!! $post->body_html !!}
            </div>
            <div class="col-md-4">
                <div class="card my-4">
                    <h5 class="card-header">Search</h5>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary" type="button">Go!</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card my-4">
                    <h5 class="card-header">Tags</h5>
                    <div class="card-body">
                        <div class="row">

                            @foreach($post->tags as $tag)

                                <div class="col-lg-6">
                                    <a href="/blog?tags={{$tag->slug}}">{{ $tag->name }}</a>
                                </div>

                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="card my-4">
                    <h5 class="card-header">Side Widget</h5>
                    <div class="card-body">
                        You can put anything you want inside of these side widgets. They are easy to use, and feature
                        the new Bootstrap 4 card containers!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <hr class="mt-5">
        <app-disqus></app-disqus>
    </div>

@endsection
