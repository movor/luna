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
// Blog
//

Route::get('blog', 'BlogPostController@index');
Route::get('blog/{slug}', 'BlogPostController@view');
Route::get('blog-post/{id}', 'BlogPostController@viewCanonical');

//
// Static pages
//

Route::match(['post', 'get'], '/contact', 'PagesController@contact');
Route::match(['post', 'get'], '/about', 'PagesController@about');

//
// Placeholder images (from external source, but server as internal)
//

Route::get('img/placeholders/{name}.jpg', function ($model) {
    $image = getPlaceholderImage($model);

    return Image::make($image)->response();
});
