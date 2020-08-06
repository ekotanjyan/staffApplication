<?php

use Illuminate\Database\Seeder;

class CampaignTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = \Faker\Factory::create();

        $campaigns = App\Campaign::all();
        foreach( $campaigns as $campaign ){

          $campaign_name = $this->faker->sentence(5);
          $campaign_description = $this->faker->text();

          foreach( supportedLocales() as $locale => $localeProps ){

            $localeString = '['.$localeProps['name'].'] ';

            App\CampaignTranslation::updateOrCreate([
              'campaign_id' => $campaign->id,
              'locale'      => $locale
            ],[
              'name' => $localeString.$campaign_name,
              'description' => $localeString.$campaign_description
            ]);

          }
        }
    }
}
