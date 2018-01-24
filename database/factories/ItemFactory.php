<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 20),
        'category_id' => rand(1, 50),
        'title' => $faker->sentence($nbWords = 1, $variableNbWords = true),
        'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true)
    ];
});
