<?php

namespace App\Console\Commands;

use App\Models\AgentGroup;
use Illuminate\Console\Command;
use App\Models\AgentGroupAssignment;
use App\Models\AgentGroupPlanning;
use App\Models\GroupPlanningCycle;
use App\Models\Agent;
use Carbon\Carbon;

class GenerateFlexiblePlanning extends Command
{
    protected $signature = 'planning:generate-horaire {--days=7}';

    protected $description = 'Génère un planning basé sur le dernier planning enregistré pour les agents du groupe flexible';

    public function handle()
    {
        $days = (int) $this->option('days');
        $today = Carbon::now('Africa/Kinshasa')->startOfDay();

        // Cycle fixe à respecter strictement
        $cycle = [
            ['horaire_id' => 5, 'is_rest_day' => 0], // jour 1
            ['horaire_id' => 7, 'is_rest_day' => 0], // jour 2
            ['horaire_id' => null, 'is_rest_day' => 1], // jour 3
        ];
        $cycleLength = count($cycle);

        $assignments = AgentGroupAssignment::where('agent_group_id', 8)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })->get();

        if ($assignments->isEmpty()) {
            $this->warn("Aucun agent assigné au groupe flexible.");
            return 0;
        }

        foreach ($assignments as $assignment) {
            $agentId = $assignment->agent_id;
            $this->info("➡ Génération du planning pour l'agent ID: $agentId");

            // Dernier jour déjà planifié
            $lastPlanning = AgentGroupPlanning::where('agent_id', $agentId)
                ->where('agent_group_id', 8)
                ->orderByDesc('date')
                ->first();

            $startDate = $lastPlanning
                ? Carbon::parse($lastPlanning->date)->addDay()
                : $today;

            // Position dans le cycle (calculée à partir de l’historique)
            $previousCount = AgentGroupPlanning::where('agent_id', $agentId)
                ->where('agent_group_id', 8)
                ->count();

            $cyclePointer = $previousCount % $cycleLength;

            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);

                // On ne crée rien si la date est déjà planifiée
                $exists = AgentGroupPlanning::where('agent_id', $agentId)
                    ->where('agent_group_id', 8)
                    ->whereDate('date', $date->toDateString())
                    ->exists();

                if ($exists) {
                    $this->line(" - {$date->toDateString()} déjà existant. ➤ Ignoré.");
                    continue;
                }

                $cycleDay = $cycle[$cyclePointer];

                AgentGroupPlanning::create([
                    'agent_id'       => $agentId,
                    'agent_group_id' => 8,
                    'date'           => $date->toDateString(),
                    'horaire_id'     => $cycleDay['horaire_id'],
                    'is_rest_day'    => $cycleDay['is_rest_day'],
                ]);

                $this->line(" - {$date->toDateString()} → " . ($cycleDay['is_rest_day'] ? "Repos" : "Horaire #{$cycleDay['horaire_id']}"));

                // Avancer dans le cycle
                $cyclePointer = ($cyclePointer + 1) % $cycleLength;
            }
        }

        $this->info("✅ Planning généré avec succès en suivant la séquence [5 → 7 → repos].");
        return 0;
    }
}

