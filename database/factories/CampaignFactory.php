<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Campaign;
use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker) {
  $randomBusiness = App\Business::all()->unique()->random();

  return [
    'user_id' => $randomBusiness->user->id,
    'business_id' => $randomBusiness->id,
    //'name' => $faker->sentence(5),
    //'description' => $faker->text(),
    'status' => '1',
    'image' => $faker->randomElement([1, 2, 3]) . '.jpg',
    'start_date' => $this->faker->dateTimeBetween('now', '+10 days')->format("Y-m-d"),
    'end_date' => $this->faker->dateTimeBetween('+11 days', '+40 days')->format("Y-m-d"),
    'target' => $faker->numberBetween(5000, 100000)
  ];
});
