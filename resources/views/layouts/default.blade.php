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

        <title>{{ env('APP_NAME') }}</title>
        {{-- TODO better logic for descr and keywords, maybe through webpack --}}
        <meta name="description" content="{{ env('APP_NAME') }}">
        <meta name="keywords" content="{{ env('APP_NAME') }}">

        @yield('canonical')

        <link rel="shortcut icon" href="{{ asset('/img/movor_logo.png') }}">

        {{-- Styles --}}

        {{ Html::style(App::environment('production') ? mix('/css/vendor.css') : '/css/vendor.css') }}
        {{ Html::style(App::environment('production') ? mix('/css/app.css') : '/css/app.css') }}

        @yield('css-head')

        {{-- /Styles --}}

        {{-- Scripts --}}

        @yield('scripts-head')

        {{-- /Scripts --}}

    </head>
    <body>

        <main>

            @include('partials.header')

            @include('partials.flash')

            @yield('content')

            @include('partials.footer')

        </main>

        {{-- Bottom Scripts --}}

        {{ Html::script(App::environment('production') ? mix('/js/vendor.js') : '/js/vendor.js') }}
        {{ Html::script(App::environment('production') ? mix('/js/app.js') : '/js/app.js') }}

        @yield('scripts-bottom')

        {{-- /Bottom Scripts --}}

    </body>
</html>
