<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Article::class, function (Faker $faker) {
    $title = rtrim($faker->unique()->sentence, '.');

    return [
        // Give Movor user a better chance
        'user_id' => chance(50, 1, DB::table('users')->inRandomOrder()->pluck('id')->first()),
        'title' => title_case($title),
        'slug' => str_slug($title),
        'summary' => rtrim($faker->realText(rand(30, 255)), '.'),
        'body' => file_get_contents(base_path('tests/Mockfiles/markdown.md')),
        'featured' => chance(20),
        'featured_image' => chance(env('DEV_SEED_IMAGE_CHANCE', 0), fetchRandomBase64Image(), null),
        'commentable' => chance(70),
        'published_at' => chance(70, \Carbon\Carbon::now(), null),
    ];
});