<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Movie;
use Faker\Generator as Faker;

$factory->define(Movie::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'filename' => $faker->md5 . '.' . $faker->fileExtension,
        'filesize' => $faker->randomNumber(5),
    ];
});
