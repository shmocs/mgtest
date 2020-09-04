<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Movie;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Movie::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraph,
        'cert' => Str::random(2),
        'class' => $faker->word,
        'duration' => $faker->randomNumber(5),
        'headline' => $faker->name,
        'movie_id' => Str::random(32),
        'last_updated' => $faker->dateTime,
        'quote' => $faker->sentence,
        'rating' => $faker->randomDigitNotNull,
        'sum' => Str::random(32),
        'synopsis' => $faker->paragraph,
        'url' => $faker->url,
        'year' => $faker->year,
    ];
});
