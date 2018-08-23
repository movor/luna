@extends('layouts.default')

@section('content')

    <div class="container article-index">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div id="filter" class="pb-5 pt-3">
                            <p class="h4 text-center pb-2">Filter by tags:</p>

                            @foreach(App\Models\Tag::ordered()->get() as $tag)

                                @php

                                    $badgeColor = in_array($tag->name, explode('~', Request::query('tag')))
                                        ? 'badge-primary'
                                        : 'badge-secondary';

                                @endphp

                                <span class="badge {{ $badgeColor }} cursor-pointer"
                                      data-name="{{ $tag->name }}"
                                >
                                    {{ $tag->name }}
                                </span>

                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="row">

                    @foreach($articles as $article)

                        <div class="col-lg-4 col-md-6">
                            <div class="card article-card mb-4">

                                {{-- Card top --}}

                                <div class="card card-inner text-white">
                                    <a href="{{ $article->getUrl() }}">
                                        <img class="card-img-top" src="{{ asset($article->featured_image->lg()) }}">
                                    </a>
                                </div>

                                {{-- /Card top --}}

                                {{-- Card body --}}

                                <a href="{{ $article->getUrl() }}">
                                    <div class="card-body">
                                        <h2 class="h4 card-title text-truncate-2">{{ $article->title }}</h2>
                                        <p class="card-text text-truncate-3">
                                            {{ $article->summary }}
                                        </p>
                                        <span class="btn btn-outline-primary">Read More</span>
                                    </div>
                                </a>

                                {{-- /Card body --}}

                                {{-- Card footer --}}

                                <div class="card-footer text-muted">
                                    <a class="h5" href="{{ url('/about') }}">{{ $article->user->name }}</a>
                                    <span class="float-right">
                                        <small class="align-text-bottom">
                                            {{ $article->published_at->format('d F, Y') }}
                                        </small>
                                    </span>
                                </div>

                                {{-- /Card footer --}}

                            </div>
                        </div>

                    @endforeach

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts-bottom')

    <script>

        // Filter by url query tags
        $('#filter .badge').click(function (event) {
            const clickedTag = $(event.target).data('name'),
                query = new URLSearchParams(window.location.search),
                queryTag = query.get('tag'),
                queryTags = queryTag !== null
                    ? queryTag.split('~')
                    : [];

            queryTags.includes(clickedTag)
                ? queryTags.splice(queryTags.indexOf(clickedTag), 1)
                : queryTags.push(clickedTag);

            queryTags.length === 0
                ? query.delete('tag')
                : query.set('tag', queryTags.join('~'));

            window.location.search = query.toString();
        });

    </script>

@endsection



