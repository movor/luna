@extends('layouts.default')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- TODO: return selected tags -->
                <h1 class="mb-4">All Blog Posts</h1>
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <img class="card-img-top" src="http://placehold.it/500x200" alt="Card image cap">
                                <div class="card-body">
                                    <h2 class="card-title">{{ $post->title }}</h2>
                                    <p class="card-text">
                                        {!! $post->summary !!}
                                    </p>
                                    <a href="/blog/{{ $post->slug }}" class="btn btn-primary">Read More</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Posted on {{ $post->published_at->format('d M Y') }}
                                    <a href="#">{{ $post->user->name }}</a>
                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>
            </div>
        </div>
    </div>

@endsection


