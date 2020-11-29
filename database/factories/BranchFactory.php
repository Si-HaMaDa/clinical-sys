<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Branch;
use Faker\Generator as Faker;

$factory->define(Branch::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'slug' => $faker->word,
        'address' => $faker->text,
        'phone' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
