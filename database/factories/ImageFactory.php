<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Photo;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Photo::class, function (Faker $faker) {
    return [
        'movie_id' => $faker->randomDigitNotNull,
        'scope' => $faker->word,
        'url' => $faker->url,
        'processed' => 0,
        'cached_file' => null,
        'w' => $faker->randomDigitNotNull,
        'h' => $faker->randomDigitNotNull,
    ];
});
