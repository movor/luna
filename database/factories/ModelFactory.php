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
// Article
//

$factory->define(App\Models\Article::class, function (Faker $faker) {
    $title = rtrim($faker->unique()->sentence, '.');

    return [
        // Give Movor user a better chance
        'user_id' => chance(50, 1, DB::table('users')->inRandomOrder()->pluck('id')->first()),
        'title' => title_case($title),
        'slug' => str_slug($title),
        'summary' => rtrim($faker->realText(rand(30, 255)), '.'),
        'body' => file_get_contents('tests/Mockfiles/markdown.md'),
        'featured' => chance(20),
        'featured_image' => fetchImage(),
        'commentable' => chance(70),
        'published_at' => chance(70, \Carbon\Carbon::now(), null),
    ];
});

//
// Tag
//

$factory->define(App\Models\Tag::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    $name = chance(30, $name . ' ' . $faker->unique()->word, $name);

    return [
        'name' => str_slug($name),
    ];
});

//
// Newsletter
//

$factory->define(App\Models\Newsletter::class, function (Faker $faker) {
    return ['email' => $faker->unique()->email];
});

//
// Helpers
//

/**
 * Fetch random image based on biggest size from image variations
 *
 * @return string
 */
function fetchImage()
{
    return chance(env('DEV_SEED_IMAGE_CHANCE', 50), function () {
        $size = explode('x', \App\Lib\ImageVariations\ImageVariations_16_9::getSizes()['xl']);

        $width = $size[0];
        $height = $size[1];

        $image = file_get_contents("https://picsum.photos/$width/$height?random");
        $base64Image = base64_encode($image);

        return 'data:image/jpg;base64,' . $base64Image;
    }, null);
}