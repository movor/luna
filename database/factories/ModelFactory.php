<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

//
// User
//

$factory->define(App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

//
// Blog Post
//

$factory->define(App\Models\BlogPost::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'summary' => $faker->sentence,
        'body' => $faker->text,
        'slug' => str_slug($title),
    ];
});

//
// Blog Tag
//

$factory->define(App\Models\BlogTag::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});