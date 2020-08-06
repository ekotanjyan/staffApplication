<?php

use Illuminate\Database\Seeder;

class ProductTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = \Faker\Factory::create();

        $products = App\Product::all();
        foreach( $products as $product ){

          $product_title = $this->faker->sentence(5);
          $product_description = $this->faker->text();

          foreach( supportedLocales() as $locale => $localeProps ){

            $localeString = '['.$localeProps['name'].'] ';

            App\ProductTranslation::updateOrCreate([
              'product_id' => $product->id,
              'locale'      => $locale
            ],[
              'title' => $localeString.$product_title,
              'description' => $localeString.$product_description
            ]);

          }
        }
    }
}
