<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AgentGroupAssignment;
use App\Models\AgentGroupPlanning;
use App\Models\GroupPlanningCycle;
use App\Models\Agent;
use Carbon\Carbon;

class GenerateFlexiblePlanning extends Command
{
    protected $signature = 'planning:generate-flexible {--days=7}';
    protected $description = 'Génère le planning pour tous les agents du groupe flexible';

    public function handle()
    {
        $days = (int) $this->option('days');
        $now = Carbon::now('Africa/Kinshasa')->startOfDay();
        $flexibleGroupId = 8;

        $assignments = AgentGroupAssignment::where('agent_group_id', $flexibleGroupId)
            ->whereDate('start_date', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get();

        if ($assignments->isEmpty()) {
            $this->warn("Aucun agent assigné au groupe flexible.");
            return;
        }

        $cycles = GroupPlanningCycle::where('agent_group_id', $flexibleGroupId)->get()->keyBy('day_index');
        if ($cycles->isEmpty()) {
            $this->error("Aucun cycle défini pour le groupe flexible !");
            return;
        }

        foreach ($assignments as $assign) {
            $groupId = $assign->agent_group_id;
            for ($i = 0; $i < $days; $i++) {
                $date = $now->copy()->addDays($i);
                $dayIndex = $date->dayOfWeekIso - 1; // ISO: Lundi = 1 → 0

                $cycle = $cycles->get($dayIndex);
                if (!$cycle) continue;

                // Vérifie si planning déjà existant pour le groupe ce jour-là
                $exists = AgentGroupPlanning::where('agent_group_id', $groupId)
                    ->where('date', $date->toDateString())
                    ->exists();

                if (!$exists) {
                    AgentGroupPlanning::create([
                        'agent_group_id' => $groupId,
                        'horaire_id'     => $cycle->horaire_id,
                        'date'           => $date->toDateString(),
                        'is_rest_day'    => $cycle->is_rest_day,
                    ]);

                    $this->info("✅ Planning généré pour le groupe $groupId à la date $date");
                } else {
                    $this->line("⏭️  Planning déjà existant pour le groupe $groupId à la date $date");
                }
            }
        }
        $this->info("🎉 Tous les plannings flexibles ont été générés.");
    }
}

