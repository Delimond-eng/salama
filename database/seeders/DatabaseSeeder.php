<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        \App\Models\Agencie::updateOrCreate([
            "id"=>1
        ], [
            "name"=>"MAMBA Security",
            "adresse"=>"9304, Av. Tombalbay, Gombe Kinshasa",
        ]);
        // \App\Models\User::factory(10)->create();
        \App\Models\User::updateOrCreate(
            ['email'=>'test@gmail.com'],
            [
             'name' => 'Gaston delimond',
             'email' => 'test@gmail.com',
             'password'=>bcrypt('test@12345'),
             'agency_id'=> 1
         ]);


    }
}