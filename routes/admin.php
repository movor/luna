<?php

// Custom crud controller methods
Route::get('newsletter/export', 'NewsletterCrudController@export');

// Blogging crud resources
CRUD::resource('user', 'UserCrudController');
CRUD::resource('blog-post', 'BlogPostCrudController');
CRUD::resource('blog-tag', 'BlogTagCrudController');
CRUD::resource('newsletter', 'NewsletterCrudController');
