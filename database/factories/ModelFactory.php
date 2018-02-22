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
        'user_id' => DB::table('users')->inRandomOrder()->pluck('id')->first(),
        'title' => $title,
        'summary' => $faker->sentence,
        'body' => file_get_contents('tests/Mockfiles/markdown.md'),
        'slug' => str_slug($title),
        'published_at' => \Carbon\Carbon::now(),
    ];
});

//
// Blog Tag
//

$factory->define(App\Models\BlogTag::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => str_slug($name)
    ];
});
