<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {
    // Custom crud controller methods
    Route::get('newsletter/export', 'NewsletterCrudController@export');

    // Crud resources
    CRUD::resource('user', 'UserCrudController');
    CRUD::resource('article', 'ArticleCrudController');
    CRUD::resource('tag', 'TagCrudController');
    CRUD::resource('newsletter', 'NewsletterCrudController');
    CRUD::resource('redirect-rule', 'RedirectRuleCrudController');
});
