<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//
// Static pages
//

Route::get('/', 'StaticPageController@index');
Route::get('/about', 'StaticPageController@about');
Route::match(['post', 'get'], '/contact', 'StaticPageController@contact');

//
// Blog
//

Route::get('blog', 'BlogPostController@index');
Route::get('blog/{slug}', 'BlogPostController@view');
Route::get('blog-post/{id}', 'BlogPostController@viewCanonical');

//
// Cached placeholder images (from external source, but served as internal)
//

Route::get('img/placeholders/{name}.jpg', function ($name) {
    $width = Request::query('width', 1280);
    $height = Request::query('height', 720);

    $cacheKey = 'placeholderImage.' . $name . '-' . $width . 'x' . $height;

    $image = \Cache::rememberForever($cacheKey, function () use ($width, $height) {
        return file_get_contents("https://picsum.photos/$width/$height?random");
    });

    return Image::make($image)->response();
});
