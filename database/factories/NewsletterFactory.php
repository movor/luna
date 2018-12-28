<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Newsletter::class, function (Faker $faker) {
    return ['email' => $faker->unique()->email];
});