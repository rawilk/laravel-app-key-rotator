<?php

use \Faker\Generator;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Rawilk\AppKeyRotator\Tests\Models\User::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'birth_date' => \Illuminate\Support\Facades\Crypt::encrypt($faker->dateTime),
        'bank_account' => \Illuminate\Support\Facades\Crypt::encrypt($faker->bankAccountNumber)
    ];
});
