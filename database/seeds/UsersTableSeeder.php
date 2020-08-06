<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::updateOrCreate([
          'email' => 'altin@test.com'
        ],[
          'name' => 'Altin',
          'email_verified_at' => now(),
          'password' => Hash::make('12345678'), // password
          'remember_token' => Str::random(10),
        ]);

        App\User::updateOrCreate([
          'email' => 'toni@test.com'
        ],[
          'name' => 'Toni',
          'email_verified_at' => now(),
          'password' => Hash::make('12345678'), // password
          'remember_token' => Str::random(10),
        ]);

        App\User::updateOrCreate([
          'email' => 'greg@test.com'
        ],[
          'name' => 'Greg',
          'email_verified_at' => now(),
          'password' => Hash::make('12345678'), // password
          'remember_token' => Str::random(10),
        ]);

        factory(App\User::class, 10)->create();
    }
}
