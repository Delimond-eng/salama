<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Models\SitePlanningConfig;
use Illuminate\Console\Command;

class GenerateSitePlanningConfigs extends Command
{
     // Nom et signature de la commande
    protected $signature = 'planning:generate-site-configs';

    // Description de la commande
    protected $description = 'Génère ou réinitialise les configurations de planning pour tous les sites';

    public function handle()
    {
        $defaultConfig = [
            'start_hour' => '21:00',
            'interval' => 1,
            'pause' => 1,
            'number_of_plannings' => 5,
        ];

        $sites = Site::all();
        $countCreated = 0;
        $countUpdated = 0;

        foreach ($sites as $site) {
            $config = SitePlanningConfig::where('site_id', $site->id)->first();

            if ($config) {
                // Met à jour la config existante si besoin (optionnel)
                $config->update($defaultConfig);
                $countUpdated++;
            } else {
                // Crée une nouvelle config
                SitePlanningConfig::create(array_merge(['site_id' => $site->id], $defaultConfig));
                $countCreated++;
            }
        }

        $this->info("Configuration de planning générée pour tous les sites.");
        $this->info("Créées : $countCreated, Mise à jour : $countUpdated");

        return 0;
    }
}
