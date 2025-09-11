<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Menu;
use App\Models\PresenceHoraire;
use App\Models\Secteur;
use App\Models\SupervisionControlElement;
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
            "name"=>"Tango Protection",
            "adresse"=>"Kinshasa",
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


         $elements = [
            ['libelle' => 'TENUE', 'description' => 'Uniforme conforme et bien porté'],
            ['libelle' => 'ATTITUDE', 'description' => 'Comportement et politesse'],
            ['libelle' => 'PROPRETE DU POSTE', 'description' => 'Hygiène et ordre du poste de garde'],
            ['libelle' => 'MOYEN DE LIASON', 'description' => 'Téléphone, radio, talkie-walkie...'],
            ['libelle' => 'EPLS', 'description' => 'Équipements de protection et de sécurité'],
            ['libelle' => 'REGISTRE', 'description' => 'Présence et remplissage du registre'],
            ['libelle' => 'FICHE DES CONSIGNES', 'description' => 'Affichage visible des consignes'],
            ['libelle' => 'CONNAISSANCE DES CONSIGNES', 'description' => 'Agent connaît les consignes de son poste'],
        ];

        foreach ($elements as $element) {
            SupervisionControlElement::updateOrCreate(['libelle' => $element['libelle']], $element);
        }

        $secteurs = [
            ["libelle"=>"KIN EST"],
            ["libelle"=>"KIN NORD",],
            ["libelle"=>"KIN SUD",],
        ];

        foreach($secteurs as $s){
            Secteur::updateOrCreate(["libelle"=>$s["libelle"]], $s);
        }
    }
}
