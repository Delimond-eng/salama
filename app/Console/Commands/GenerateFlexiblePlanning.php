<?php

namespace App\Console\Commands;

use App\Models\AgentGroupAssignment;
use App\Models\AgentGroupPlanning;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateFlexiblePlanning extends Command
{
   protected $signature = 'planning:generate-horaire {--days=7}';
    protected $description = 'Génère un planning flexible basé sur les 2 derniers jours du cycle hebdomadaire pour chaque agent individuellement.';

    protected $horaireMap = [
        'J'   => 5,
        'S'   => 7,
        'OFF' => null,
    ];

    protected $codeMap = [
        5    => 'J',
        7    => 'S',
        null => 'OFF',
    ];

    public function handle()
    {
        $days = (int) $this->option('days');
        $today = Carbon::now('Africa/Kinshasa')->startOfDay();

        $assignments = AgentGroupAssignment::where('agent_group_id', 8)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })->get();

        if ($assignments->isEmpty()) {
            $this->warn("Aucun agent assigné au groupe flexible.");
            return 0;
        }

        foreach ($assignments as $assignment) {
            $agent = $assignment->agent;
            $matricule = $agent->matricule;

            $plannings = AgentGroupPlanning::where('agent_id', $agent->id)
                ->where('agent_group_id', 8)
                ->orderBy('date')
                ->get();

            if ($plannings->count() < 2) {
                $this->warn("Pas assez d'historique pour $matricule.");
                continue;
            }

            $lastWeekPlannings = $plannings->slice(-7);
            $lastWeekCodes = $lastWeekPlannings->map(function ($p) {
                return $this->codeMap[$p->horaire_id] ?? 'OFF';
            })->implode('-');

            $lastTwoPlannings = $plannings->slice(-2);
            $lastTwoCodes = $lastTwoPlannings->map(function ($p) {
                return $this->codeMap[$p->horaire_id] ?? 'OFF';
            })->values()->toArray();

            $nextCycle = $this->getNextCycleFromLastTwo($lastTwoCodes);

            if (!$nextCycle) {
                $this->warn("Cycle inconnu pour $matricule avec les jours: " . implode('-', $lastTwoCodes));
                continue;
            }

            $startDate = Carbon::parse($plannings->last()->date)->addDay();

            $this->info("🧾 Agent $matricule :");
            $this->line(" - Dernière semaine : $lastWeekCodes");
            $this->line(" - Prochain cycle  : " . implode('-', $nextCycle));

            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);

                $exists = AgentGroupPlanning::where('agent_id', $agent->id)
                    ->where('agent_group_id', 8)
                    ->whereDate('date', $date->toDateString())
                    ->exists();

                if ($exists) continue;

                $code = $nextCycle[$i % 7];
                AgentGroupPlanning::create([
                    'agent_id'       => $agent->id,
                    'agent_group_id' => 8,
                    'date'           => $date->toDateString(),
                    'horaire_id'     => $this->horaireMap[$code],
                    'is_rest_day'    => $code === 'OFF',
                ]);
            }
        }

        return 0;
    }

    protected function getNextCycleFromLastTwo(array $lastTwo): ?array
    {
        $cycleRotations = [
            'J-OFF' => ['S', 'J', 'OFF', 'S', 'J', 'OFF', 'S'],
            'OFF-S' => ['J', 'S', 'OFF', 'J', 'S', 'OFF', 'J'],
            'OFF-J' => ['S', 'J', 'OFF', 'S', 'J', 'OFF', 'S'],
            'S-J'   => ['OFF', 'S', 'J', 'OFF', 'S', 'J', 'OFF'],
            'J-S'   => ['OFF', 'J', 'S', 'OFF', 'J', 'S', 'OFF'],
            'S-OFF' => ['J', 'S', 'OFF', 'J', 'S', 'OFF', 'J'],
        ];

        $key = implode('-', $lastTwo);
        return $cycleRotations[$key] ?? null;
    }
}

