<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Menu;
use App\Models\PresenceHoraire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

         PresenceHoraire::updateOrCreate(
                [
                    "libelle"=>"Normale",
                ],
                [
                    "libelle"=>"Normale",
                    "started_at"=>"07:00",
                    "ended_at"=>"16:30",
                ],
            );

            $menus = [
            'Gestion Patrouilles',
            'Gestion Sites',
            'Gestion Agents',
            'Gestion Tâches',
            'Gestion visiteurs',
            'Gestion Présences',
            'Gestion Planning',
            'Requêtes',
            'Communiqués',
            'Signalements',
            'Gestion Utilisateurs',
            'Rapport des logs',
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(["name"=>$menu],[
                'name' => $menu,
                'slug' => Str::slug($menu),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
