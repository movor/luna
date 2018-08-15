<?php

// Custom crud controller methods
Route::get('newsletter/export', 'NewsletterCrudController@export');

// Crud resources
CRUD::resource('user', 'UserCrudController');
CRUD::resource('article', 'ArticleCrudController');
CRUD::resource('tag', 'TagCrudController');
CRUD::resource('newsletter', 'NewsletterCrudController');
CRUD::resource('redirect-rule', 'RedirectRuleCrudController');