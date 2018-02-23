@php

    use Artesaos\SEOTools\Facades\SEOMeta;
    use Artesaos\SEOTools\Facades\OpenGraph;
    use Artesaos\SEOTools\Facades\TwitterCard;

    $name = Request::segment(1);
    $url = Request::url();
    $title = $name . ' | ' . env('APP_NAME');

    SEOMeta::setTitle($name . ' | ' . env('APP_NAME'));
    SEOMeta::setDescription('A plethora of blog posts about web-development');
    SEOMeta::setCanonical($url);

    OpenGraph::setUrl($url);
    OpenGraph::setTitle($title);

    TwitterCard::setTitle('Blog @_movor');
    TwitterCard::setSite('@_movor');

@endphp