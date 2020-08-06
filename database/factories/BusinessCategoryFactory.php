<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BusinessCategory;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(BusinessCategory::class, function (Faker $faker) {
    return [
        'name' => 'Category ' . $faker->words(3),
        'description' => $faker->paragraphs(),
    ];
});
