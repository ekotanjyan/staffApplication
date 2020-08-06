<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
          UsersTableSeeder::class,
          BusinessCategoriesTableSeeder::class,
          ProductCategoriesSeeder::class,
          BusinessesTableSeeder::class,
          ProductsTableSeeder::class,
          ProductTranslationsTableSeeder::class,
          CampaignsTableSeeder::class,
          CampaignTranslationsTableSeeder::class
        ]);
    }
}
