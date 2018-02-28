<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>

        @if (App::environment('production'))
            // TODO
            // Google analytics
        @endif

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {!! SEOMeta::generate() !!}
        {!! OpenGraph::generate() !!}
        {!! Twitter::generate() !!}

        <link rel="shortcut icon" href="{{ asset('/img/movor_logo.png') }}">

        {{-- Styles --}}

        {{ Html::style(App::environment('production') ? mix('/css/vendor.css') : '/css/vendor.css') }}
        {{ Html::style(App::environment('production') ? mix('/css/default.css') : '/css/default.css') }}

        @yield('css-head')

        {{-- /Styles --}}

        {{-- Scripts --}}

        @yield('scripts-head')

        {{-- /Scripts --}}

    </head>
    <body>
        <div id="app">

            <div class="py-5"></div>

            @include('partials.header')

            @includeWhen(Session::has('message'), 'partials.flash')

            <main>@yield('content')</main>

            <div class="py-3"></div>

            @include('partials.footer')

        </div>

        {{-- Bottom Scripts --}}

        {{ Html::script(App::environment('production') ? mix('/js/vendor.js') : '/js/vendor.js') }}
        {{ Html::script(App::environment('production') ? mix('/js/app.js') : '/js/app.js') }}

        @yield('scripts-bottom')

        {{-- /Bottom Scripts --}}

    </body>
</html>
