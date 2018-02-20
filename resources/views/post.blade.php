@extends('layouts.default')

@section('content')
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
@endsection

@section('canonical')
    <link rel="canonical" href="{{url($post->getCanonicalUrl())}}">
@endsection
