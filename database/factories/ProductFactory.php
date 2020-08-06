<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
  $user = App\User::all()->unique()->random();
  $productCategory = App\ProductCategory::all()->unique()->random();
  return [
    'user_id' => $user->id,
    //'title' => $faker->sentence(3),
    //'description' => $faker->text(),
    'category_id' => $productCategory->id,
    'units' => $faker->unique()->numberBetween(10, 1000),
    'price' => $faker->unique()->numberBetween(250, 25000),
    'image' => $faker->randomElement([1, 2, 3]) . '.jpg',
    'start_date' => $this->faker->dateTimeBetween('now', '+10 days')->format("Y-m-d"),
    'end_date' => $this->faker->dateTimeBetween('+11 days', '+40 days')->format("Y-m-d"),
    'active' => $this->faker->numberBetween(0, 1) ? false : true,
    'featured' => $this->faker->numberBetween(0, 1) ? false : true
  ];
});
