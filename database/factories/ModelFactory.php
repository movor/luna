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
    $title = rtrim($faker->unique()->sentence, '.');

    return [
        // Give Movor user a better change
        'user_id' => chance(50, function () {
            // Movor user id
            return 1;
        }, DB::table('users')->inRandomOrder()->pluck('id')->first()),
        'title' => $title,
        'summary' => rtrim($faker->realText(255), '.'),
        'body' => file_get_contents('tests/Mockfiles/markdown.md'),
        'slug' => str_slug($title),
        'published_at' => chance(70, function () {
            return \Carbon\Carbon::now();
        }),
    ];
});

//
// Blog Tag
//

$factory->define(App\Models\BlogTag::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'name' => $name,
        'slug' => str_slug($name)
    ];
});
