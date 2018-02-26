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
    return view('index', [
        'title' => '',
        'description' => 'Main page of Movor, Belgrade based, software development team.',
    ]);
});

//
// Static pages
//

Route::get('contact', function () {
    //asset($post->featured_image);
    return view('static_pages.contact', [
        'description' => 'Feel free to contact us using webform or mail!'
    ]);
});

//
// Blog
//

Route::get('blog', 'BlogPostController@index');
Route::get('blog/{slug}', 'BlogPostController@view');
Route::get('blog-post/{id}', 'BlogPostController@viewCanonical');

//
// Placeholder images
//

Route::get('img/placeholders/{name}.jpg', function ($model) {
    $image = getPlaceholderImage($model);

    return Image::make($image)->response();
});
