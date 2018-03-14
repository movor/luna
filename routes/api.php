<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication
Route::post('/user/register', 'Api\UserController@register');
Route::get('/user/current', 'Api\UserController@current')->middleware('auth:api');

// Blog
Route::get('/blog-post', 'Api\BlogPostController@index');
Route::get('/blog-post/{id}', 'Api\BlogPostController@show');