<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Business;
use Faker\Generator as Faker;

$factory->define(Business::class, function (Faker $faker) {
    // id 1: altin
    // id 2: antonio
    // id 3: greg

    //let's make sure that the most businesses will be ours,
    //so that we besome instantly rich :P
    $minUserID = 1;
    $maxUserID = 5;

    $user = App\User::where(function ($query) use ($minUserID, $maxUserID) {
        $query->where('id', '>=', $minUserID);
        $query->where('id', '<=', $maxUserID);
    })->get()->unique()->random();

    return [
      'user_id' => $user->id,
      'name' => $faker->company('catchPhrase'),
      'vatid' => $faker->unique()->numberBetween(1, 2000),
      'description' => $faker->text(),
      'zip' => $faker->postcode,
      'address' => $faker->address('streetAddress'),
      'city' => $faker->address('city'),
      'country' => $faker->address('country'),
      'category_id' => App\BusinessCategory::all()->unique()->random()->id
    ];
});
