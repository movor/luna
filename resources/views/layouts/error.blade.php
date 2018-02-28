<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
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
        {{ Html::style(App::environment('production') ? mix('/css/error.css') : '/css/error.css') }}

        @yield('css-head')

        {{-- /Styles --}}

        {{-- Scripts --}}

        @yield('scripts-head')

        {{-- /Scripts --}}

    </head>
    <body>
        <div id="app">
            <main>@yield('content')</main>
        </div>

        {{-- Bottom Scripts --}}

        {{ Html::script(App::environment('production') ? mix('/js/vendor.js') : '/js/vendor.js') }}
        {{ Html::script(App::environment('production') ? mix('/js/app.js') : '/js/app.js') }}

        @yield('scripts-bottom')

        {{-- /Bottom Scripts --}}

    </body>
</html>
