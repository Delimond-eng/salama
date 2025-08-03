<?php

namespace App\Console\Commands;

use App\Models\AgentGroupAssignment;
use App\Models\AgentGroupPlanning;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateFlexiblePlanning extends Command
{
    protected $signature = 'planning:generate-horaire {--days=7}';
    protected $description = 'Génère un planning flexible basé sur la logique du cycle personnel de chaque agent.';
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

            if ($plannings->count() < 7) {
                $this->warn("Pas assez d'historique pour $matricule.");
                continue;
            }

            $lastWeekPlannings = $plannings->slice(-7);
            $lastWeekCodes = $lastWeekPlannings->map(function ($p) {
                return $this->codeMap[$p->horaire_id] ?? 'OFF';
            })->values()->toArray();

            $startDate = Carbon::parse($plannings->last()->date)->addDay();

            $this->info("📜 Agent $matricule :");
            $this->line(" - Dernière semaine : " . implode('-', $lastWeekCodes));

            $cycle = array_slice($lastWeekCodes, -3); // Ex : S, J, OFF
            if (count(array_unique($cycle)) < 3 || !in_array('OFF', $cycle)) {
                $this->warn("Cycle non valide pour $matricule. Données : " . implode('-', $cycle));
                continue;
            }

            $generatedCodes = [];
            for ($i = 0; $i < $days; $i++) {
                $code = $cycle[$i % 3];
                $generatedCodes[] = $code;

                $date = $startDate->copy()->addDays($i);
                $exists = AgentGroupPlanning::where('agent_id', $agent->id)
                    ->where('agent_group_id', 8)
                    ->whereDate('date', $date->toDateString())
                    ->exists();

                if ($exists) continue;

                AgentGroupPlanning::create([
                    'agent_id'       => $agent->id,
                    'agent_group_id' => 8,
                    'date'           => $date->toDateString(),
                    'horaire_id'     => $this->horaireMap[$code],
                    'is_rest_day'    => $code === 'OFF',
                ]);
            }

            $this->line(" - Semaines générées :");
            for ($w = 0; $w < ceil($days / 7); $w++) {
                $week = array_slice($generatedCodes, $w * 7, 7);
                $this->line("   Semaine " . ($w + 1) . ": " . implode(' | ', $week));
            }
        }

        return 0;
    }
}

