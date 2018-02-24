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

Route::get('/', function () {
    return view('index');
});

//
// Static pages
//

Route::get('contact', function () {
    //asset($post->featured_image);
    return view('static_pages.contact');
});

//
// Blog
//

Route::get('blog', 'BlogPostController@index');
Route::get('blog/{slug}', 'BlogPostController@view');
Route::get('blog-post/{id}', 'BlogPostController@viewCanonical');

//
// Temp
//

Route::get('img/remote/{name}.jpg', function ($name) {
    $image = getPlaceholderImage($name);

    return Image::make($image)->response();
});
