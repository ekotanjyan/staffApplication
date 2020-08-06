<?php

use Illuminate\Database\Seeder;

class ProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categories')->insert([
            
            [
                'en'=>'Product',
                'de'=>'Produkt',
                'es'=>'Producto',
                'it'=>'Prodotto',
                'fr'=>'Produit',
                'usage'=>'single'
            ],
            [
                'en'=>'Service',
                'de'=>'Dienstleistung',
                'es'=>'Servicio',
                'it'=>'Servizio',
                'fr'=>'Service',
                'usage'=>'single'
            ],
            [
                'en'=>'Discount',
                'de'=>'Rabatt',
                'es'=>'Descuento',
                'it'=>'Sconto',
                'fr'=>'Remise',
                'usage'=>'single'
            ],    
            [
                'en' => 'Gift voucher',
                'de'=>'Geschenkgutschein',
                'es'=>'Vale regalo',
                'it'=>'Buono regalo',
                'fr'=>'Chèque cadeau',
                'usage'=>'single'
            ],
            [
                'en' => 'Multiple use voucher',
                'de'=>'Rabattkonto',
                'es'=>'Cuenta de descuento',
                'it'=>'Buono d’acquisto (da usare in più volte)',
                'fr'=>'Compte de remise',
                'usage'=>'multiple'
            ]
            
        ]);
    }
}
