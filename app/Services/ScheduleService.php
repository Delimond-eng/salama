<?php
namespace App\Services;

use App\Http\Controllers\EmailController;
use App\Models\Schedules;
use App\Models\Patrol;
use App\Models\Area;
use App\Models\PatrolScan;
use App\Models\Site;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ScheduleService
{
    public function verifySchedules()
    {
        $now = Carbon::now('Africa/Kinshasa');
        $toleranceMinutes = 5;

        $schedules = Schedules::where('status', 'actif')
            ->whereDate('date', '<=', $now->toDateString())
            ->get();

        Log::info("🕒 Vérification des plannings à {$now->format('Y-m-d H:i:s')}");
        Log::info("Nombre de plannings à vérifier : " . $schedules->count());

        foreach ($schedules as $schedule) {
            try {
                Log::info("🔍 Vérification du planning ID: {$schedule->id}");

                $start = $this->parseDateTime($schedule->date, $schedule->start_time);
                $end = $schedule->end_time
                    ? $this->parseDateTime($schedule->date, $schedule->end_time)
                    : $now;

                $toleranceStart = $start->copy()->subMinutes($toleranceMinutes);
                $toleranceEnd = $end->copy()->addMinutes($toleranceMinutes);

                Log::info("Plage tolérée : {$toleranceStart} → {$toleranceEnd}");

                $patrol = Patrol::with('agent')
                    ->where('site_id', $schedule->site_id)
                    ->whereBetween('started_at', [$toleranceStart, $toleranceEnd])
                    ->latest()
                    ->first();

                if (!$patrol) {
                    if ($now->gt($toleranceEnd)) {
                        Log::warning("❌ Aucune patrouille trouvée pour le planning ID {$schedule->id}.");
                        $this->sendFailureEmail($schedule, null, null, $now);
                        $schedule->status = 'fail';
                        $schedule->save();
                    }
                    continue;
                }

                $startedAt = Carbon::parse($patrol->started_at)->setTimezone('Africa/Kinshasa');

                if ($startedAt->lt($start) || $startedAt->gt($end)) {
                    if ($now->gt($toleranceEnd)) {
                        Log::warning("⚠️ Patrouille hors créneau strict (start={$start}, end={$end}).");
                        $this->sendFailureEmail($schedule, $patrol->agent ?? null, $patrol->photo ?? null, $now);
                        $schedule->status = 'fail';
                        $schedule->save();
                    }
                    continue;
                }

                // Vérification des zones scannées
                $allAreas = Area::where('site_id', $schedule->site_id)->pluck('id')->toArray();
                $scannedAreas = PatrolScan::where('patrol_id', $patrol->id)->pluck('area_id')->unique()->toArray();

                $totalAreas = count($allAreas);
                $scannedCount = count($scannedAreas);
                $ratio = $totalAreas > 0 ? ($scannedCount / $totalAreas) : 0;

                $newStatus = 'fail';
                if ($scannedCount > 0) {
                    $newStatus = ($ratio < 1.0 && $ratio >= 0.5) ? 'partial' : 'success';
                } else {
                    Log::warning("Patrouille ID {$patrol->id} sans scan de zone.");
                }

                if ($newStatus === 'fail') {
                    $this->sendFailureEmail($schedule, $patrol->agent ?? null, $patrol->photo ?? null, $now);
                }

                // Ne pas écraser un échec existant
                if ($schedule->status !== 'fail') {
                    $schedule->status = $newStatus;
                    $schedule->save();
                    Log::info("✅ Planning ID {$schedule->id} → statut mis à jour : {$newStatus}");
                }

            } catch (\Exception $e) {
                Log::error("💥 Erreur sur le planning ID {$schedule->id} : " . $e->getMessage());
            }
        }
    }



    protected function parseDateTime($date, $time)
    {
        try {
            $date = trim($date);
            $time = trim($time);

            // Si `$time` contient déjà une date complète, on l'utilise directement
            if (preg_match('/\d{4}-\d{2}-\d{2}/', $time)) {
                return Carbon::parse($time, 'Africa/Kinshasa');
            }

            // Sinon, on assemble la date et l'heure
            if (strlen($date) > 10) {
                $date = Carbon::parse($date)->format('Y-m-d');
            }

            $datetime = "{$date} {$time}";

            return Carbon::parse($datetime, 'Africa/Kinshasa');

        } catch (\Exception $e) {
            Log::error("⛔ Erreur de parsing sur date={$date}, time={$time} : " . $e->getMessage());
            throw $e;
        }
    }



    protected function sendFailureEmail($schedule, $agent, $photo, $now)
    {
        $site = Site::find($schedule->site_id);
        if ($site && $site->emails) {
            (new EmailController())->sendMail([
                "emails" => $site->emails,
                "title" => "Patrouille non respectée",
                "photo" => $photo,
                "agent" => $agent ? ($agent->matricule . ' - ' . $agent->fullname) : null,
                "site" => $site->code . ' - ' . $site->name,
                "date" => $now->format("d/m/y H:i")
            ]);
        }
    }
}
