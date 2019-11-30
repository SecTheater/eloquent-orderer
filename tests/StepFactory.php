<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(Step::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
    ];
});
