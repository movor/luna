@extends('layouts.error')

@section('content')


    <div class="container">
        <div class="text-center my-5 py-5">
            <div class="top mb-5">
                <span style="font-size: 200px" class="fa fa-frown-o text-primary"></span>
            </div>
            <div class="middle mb-5">
                <h1>{{ $title ?? 'Ooops! Something went wrong' }}</h1>
                <p class="lead">{{ $body ?? 'Hold tight, we are already working on it' }}</p>
            </div>
            <div class="bottom">
                <a href="/" class="btn btn-lg btn-outline-primary">Back Home</a>
            </div>
        </div>
    </div>

@endsection