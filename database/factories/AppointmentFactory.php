<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Appointment;
use Faker\Generator as Faker;

$factory->define(Appointment::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'email' => $faker->word,
        'phone' => $faker->word,
        'gender' => $faker->word,
        'birth' => $faker->word,
        'address' => $faker->word,
        'patient_id' => $faker->word,
        'doctor_id' => $faker->word,
        'date' => $faker->word,
        'time' => $faker->word,
        'payment_method' => $faker->word,
        'offer_id' => $faker->word,
        'tax_id' => $faker->word,
        'note' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
