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
        $now = Carbon::now()->addHour(); // UTC +1 si serveur en UTC
        $toleranceMinutes = 5;

        $schedules = Schedules::whereDate('date', $now->toDateString())
            ->whereNotIn('status', ['success', 'partial', 'fail'])
            ->get();

        foreach ($schedules as $schedule) {
            try {
                Log::info("Schedule ID: {$schedule->id} | Date: {$schedule->date} | Start: {$schedule->start_time} | End: {$schedule->end_time}");

                $start = $this->parseDateTime($schedule->date, $schedule->start_time)->addHour();
                $end = $schedule->end_time
                    ? $this->parseDateTime($schedule->date, $schedule->end_time)->addHour()
                    : $now;

                $toleranceStart = $start->copy()->subMinutes($toleranceMinutes);
                $toleranceEnd = $end->copy()->addMinutes($toleranceMinutes);

                $patrol = Patrol::with("agent")
                    ->where('site_id', $schedule->site_id)
                    ->whereBetween('started_at', [$toleranceStart, $toleranceEnd])
                    ->latest()
                    ->first();

                $newStatus = 'fail';

                if (!$patrol) {
                    if ($now->gt($toleranceEnd)) {
                        $this->sendFailureEmail($schedule, null, null, $now);
                        $schedule->status = 'fail';
                        $schedule->save();
                    }
                    continue;
                }

                $startedAt = Carbon::parse($patrol->started_at);
                if ($startedAt->lt($start) || $startedAt->gt($end)) {
                    if ($now->gt($toleranceEnd)) {
                        $this->sendFailureEmail($schedule, $patrol->agent ?? null, $patrol->photo ?? null, $now);
                        $schedule->status = 'fail';
                        $schedule->save();
                    }
                    continue;
                }

                $allAreas = Area::where('site_id', $schedule->site_id)->pluck('id')->toArray();
                $scannedAreas = PatrolScan::where('patrol_id', $patrol->id)->pluck('area_id')->unique()->toArray();

                if (empty($scannedAreas)) {
                    $newStatus = 'fail';
                } elseif (count($scannedAreas) < count($allAreas)) {
                    $newStatus = 'partial';
                } else {
                    $newStatus = 'success';
                }

                if ($newStatus === 'fail') {
                    $this->sendFailureEmail($schedule, $patrol->agent ?? null, $patrol->photo ?? null, $now);
                }

                $schedule->status = $newStatus;
                $schedule->save();

            } catch (\Exception $e) {
                Log::error("Erreur lors de la vérification du schedule ID {$schedule->id} : " . $e->getMessage());
            }
        }
    }

    private function parseDateTime($date, $time)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', "{$date} {$time}");
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
