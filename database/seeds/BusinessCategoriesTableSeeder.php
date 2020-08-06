<?php

use Illuminate\Database\Seeder;

class BusinessCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_categories')->insert([
            
            [
                'en'=>'Culture',
                'de'=>'Kultur',
                'es'=>'Cultura',
                'it'=>'Cultura',
                'fr'=>'Culture'
            ],
            [
                'en'=>'Hotel / Tourism',
                'de'=>'Hotel / Tourismus',
                'es'=>'Hotel / Turismo',
                'it'=>'Hotel / Turismo',
                'fr'=>'Hôtel / tourisme'
            ],
            [
                'en'=>'Local business',
                'de'=>'Lokales Geschäft',
                'es'=>'Negocio local',
                'it'=>'Attività locale',
                'fr'=>'Entreprise locale'
            ],    
            [
                'en' => 'Restaurants',
                'de'=>'Restaurants',
                'es'=>'Restaurantes',
                'it'=>'Ristoranti',
                'fr'=>'Restaurants'
            ],
            [
                'en' => 'Services',
                'de'=>'Dienstleistungen',
                'es'=>'Servicios',
                'it'=>'Servizi',
                'fr'=>'Prestations de service'
            ],
            [
                'en' => 'Other',
                'de'=>'Andere',
                'es'=>'Sin categoria',
                'it'=>'Non categorizzato',
                'fr'=>'Général'
            ]
            
        ]);
    }
}
