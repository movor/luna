@extends('layouts.default')

@section('content')

    <div class="container article-index">
        <div class="row">
            <div class="col-md-12">

                <h1 class="mb-4">{{ $title }}</h1>

                <div class="row">

                    @foreach($articles as $article)

                        <div class="col-lg-6">
                            <div class="card mb-4">

                                {{-- Inner card --}}

                                <div class="card card-inner text-white">
                                    <img class="card-img-top" src="{{ asset($article->featured_image->source()) }}">
                                    <div class="lead card-img-overlay d-flex align-items-end justify-content-end cursor-pointer"
                                         data-link="{{ $article->getUrl() }}"
                                    >
                                        @foreach($article->tags as $tag)

                                            <a href="{{ url("article?tag=$tag->name") }}"
                                               class="badge badge-primary ml-1"
                                            >
                                                {{ $tag->name }}
                                            </a>

                                        @endforeach

                                    </div>
                                </div>

                                {{-- /Inner card --}}

                                <a href="{{ $article->getUrl() }}">
                                    <div class="card-body">
                                        <h2 class="card-title text-truncate-2">{{ $article->title }}</h2>
                                        <p class="card-text text-justify text-truncate-3">
                                            {{ $article->summary }}
                                        </p>
                                        <span class="btn btn-primary">Read More</span>
                                    </div>
                                </a>
                                <div class="card-footer text-muted">
                                    <a href="/about">{{ $article->user->name }}</a>
                                    <span class="float-right">
                                        <small class="align-text-bottom">
                                            {{ $article->published_at->format('d F, Y') }}
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

@section('scripts-bottom')

    <script type="javascript">

        // Make card image a link
        $('.card-img-overlay').click(function (event) {
            window.location = $(event.target).data('link');
        })

    </script>

@endsection



