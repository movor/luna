<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Tag::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    $name = chance(30, $name . ' ' . $faker->unique()->word, $name);

    return [
        'name' => str_slug($name),
    ];
});