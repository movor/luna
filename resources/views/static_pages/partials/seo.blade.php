@php

    use Artesaos\SEOTools\Facades\SEOMeta;
    use Artesaos\SEOTools\Facades\OpenGraph;
    use Artesaos\SEOTools\Facades\TwitterCard;

    // TODO: if static page has more than a word in the name - title pages returns error. fix

        $url = url(Request::url());
    // if variable title isn't provided at route create it
    if (!isset($title)) {
        $name = ucfirst(Request::segment(1));
        $title = $name . ' | ' . env('APP_NAME');
    // if variable provided at route as empty
    } else if (empty($title)){
        $title = env('APP_NAME');
    } else {
        $title = $title . ' | ' . env('APP_NAME');
    }

    SEOMeta::setTitle($title);
    SEOMeta::setDescription($description);
    SEOMeta::setCanonical($url);

    OpenGraph::setUrl($url);
    OpenGraph::setTitle($title);

    TwitterCard::setTitle($title);
    TwitterCard::setSite('@_movor');

@endphp
