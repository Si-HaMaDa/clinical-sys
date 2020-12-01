<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'type' => $faker->randomElement($array = array('doctor', 'staff', 'patient')),
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'gender' => $faker->randomElement($array = array('m', 'f', 'o')),
        'age' => $faker->randomDigitNotNull,
        'image' => $faker->imageUrl,
        'email' => $faker->faker->unique()->safeEmail,
        'email_verified_at' => $faker->date('Y-m-d H:i:s'),
        'password' => $faker->word,
        'remember_token' => Str::random(10),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
