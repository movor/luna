<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/"><img src="{{ asset('img/movor_logo.svg') }}" alt="{{ env('APP_NAME') . ' Logo' }}"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto font-weight-bold">
                <li class="nav-item {{ Request::segment(1) == '' ? 'active' : '' }}">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'about' ? 'active' : '' }}">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'contact' ? 'active' : '' }}">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'newsletter' ? 'active' : '' }}">
                    <a class="nav-link" href="/newsletter">Newsletter</a>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'article' ? 'active' : '' }}">
                    <a class="nav-link" href="/article">Blog</a>
                </li>
            </ul>
        </div>
    </div>
</nav>