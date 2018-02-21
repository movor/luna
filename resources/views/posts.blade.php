@extends('layouts.default')

@section('content')
    @foreach($posts as $post)
        <hr>
        <div>{{ $post->title }}</div>
        <div>{{ $post->user->name }}</div>
        <hr>
        <div>
            {!! $post->body_html !!}
        </div>
        <div>
            {{$post->published_at}}
        </div>
    @endforeach
@endsection
