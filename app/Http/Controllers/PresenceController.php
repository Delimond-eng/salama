<?php

namespace App\Http\Controllers;

use App\Models\PresenceHoraire;
use App\Models\PresenceAgents;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Site;
use App\Models\Agent;
use App\Models\AgentGroup;
use App\Models\PresenceSupervisorControl;
use App\Models\PresenceSupervisorSite;
use App\Models\ScheduleSupervisor;
use App\Models\ScheduleSupervisorSite;
use Illuminate\Http\JsonResponse;

class PresenceController extends Controller
{
    public function createHoraire(Request $request){
        try{
            $data = $request->validate([
                "libelle"=>"required|string",
                "started_at"=>"required|string",
                "ended_at"=>"required|string",
                "tolerence"=>"nullable|string",
            ]);
            $response = PresenceHoraire::updateOrCreate(
                [
                    "id"=>$request->id,
                ],
                $data
            );
            
            return response()->json([
                "status"=>"success",
                "result"=>$response
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }


    public function createGroup(Request $request){
        try{
            $data = $request->validate([
                "libelle"=>"required|string",
                "horaire_id"=>"required|int|exists:presence_horaires,id",
            ]);
            $response = AgentGroup::updateOrCreate(
                [
                    "id"=>$request->id,
                ],
                $data
            );
            
            return response()->json([
                "status"=>"success",
                "result"=>$response
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }



    /*
    *Lionnel nawej
    *Creation de la presence des agents
    *16:10/15-05-2025
    */
    public function createPresenceAgent(Request $request)
    {
        try {
            $data = $request->validate([
                "matricule" => "required|string|exists:agents,matricule",
                "heure" => "required|string",
                "status_photo" => "nullable|string",
                "coordonnees" => "required|string",
            ]);

            $agent = Agent::with("groupe.horaire")->where('matricule', $data['matricule'])->firstOrFail();
            $now = Carbon::now()->setTimezone('Africa/Kinshasa');

            $lat1 = null;
            $lng1 = null;
            $commentaire_distance = "Pas de site défini.";
            $distance = null;
            $site = null;

            // Extraire les coordonnées de l'agent
            if (!empty($data['coordonnees'])) {
                list($lat1, $lng1) = explode(',', $data['coordonnees']);
            }

            // Si agent a un site assigné
            if ($agent->site_id) {
                $site = Site::find($agent->site_id);
            }
            // Sinon, on essaie de deviner à partir des coordonnées
            else if ($lat1 && $lng1) {
                $sites = Site::all();
                $siteProche = null;
                $minDistance = PHP_INT_MAX;
                foreach ($sites as $s) {
                    if (!$s->latlng) continue;
                    list($lat2, $lng2) = explode(',', $s->latlng);
                    $d = (new AppManagerController())->calculateDistance($lat1, $lng1, $lat2, $lng2);
                    if ($d < $minDistance) {
                        $minDistance = $d;
                        $siteProche = $s;
                    }
                }
                // Si on trouve un site proche à moins de 200m
                if ($siteProche && $minDistance <= 400) {
                    $site = $siteProche;
                    $agent->site_id = $siteProche->id;
                } else {
                    $agent->site_id = 0; // ou null selon ta base
                }

            }

            // Gestion de la distance et du commentaire
            if ($site) {
                list($lat2, $lng2) = explode(',', $site->latlng);
                $distance = app(AppManagerController::class)->calculateDistance($lat1, $lng1, $lat2, $lng2);
                $proximite = $distance <= 100 ? "dans le site" : "hors du site";
                $commentaire_proximite = request('is_sortie') ?
                    ($distance <= 400 ? "sorti du site" : "pas dans le site à la sortie") :
                    ($distance <= 400 ? "arrivé dans le site" : "pas arrivé dans le site");
                $commentaire_distance = "$commentaire_proximite - " . round($distance) . " mètres du site";
            }

            $horaire = $agent->groupe->horaire ? PresenceHoraire::find($agent->groupe->horaire_id) : null;

            $photoField = request('is_sortie') ? 'photos_fin' : 'photos_debut';
            $statusPhotoField = request('is_sortie') ? 'status_photo_fin' : 'status_photo_debut';
            $timeField = request('is_sortie') ? 'ended_at' : 'started_at';

            $filename = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/presence_photos'), $filename);
                $photoUrl = url('uploads/presence_photos/' . $filename);
            }

            $presence = PresenceAgents::where('agent_id', $agent->id)->where('status', 'debut')->latest()->first();

            if (!$presence) {
                $retard = 'non';
                if ($horaire) {
                    $retard = (strtotime($data['heure']) - strtotime($horaire->started_at)) > 900 ? 'oui' : 'non';
                } else {
                    $commentaire_distance .= " | Sans horaire précis.";
                }

                $presence = PresenceAgents::create([
                    'agent_id' => $agent->id,
                    'site_id' => $agent->site_id ?? 0,
                    'horaire_id' => $agent->groupe->horaire_id,
                    'started_at' => $data['heure'],
                    'photos_debut' => $photoUrl ?? null,
                    'status_photo_debut' => $data['status_photo'] ?? null,
                    'retard' => $retard,
                    'commentaires' => $commentaire_distance,
                    'status' => 'debut'
                ]);

                if($site->emails){
                    (new EmailController())->sendMail([
                        "emails" => $site->emails,
                        "title" => "Présence signalée",
                        "photo" => $photoUrl,
                        "agent" => $agent->matricule . ' - ' . $agent->fullname,
                        "site" => $site->code . ' - ' . $site->name,
                        "date" => $now->format("d/m/y H:i")
                    ]);
                }

                return response()->json([
                    "status" => "success",
                    "message" => "Présence début enregistrée.",
                    "result" => $presence
                ]);
            } else {
                $start = new \DateTime($presence->started_at);
                $end = new \DateTime($data['heure']);
                if ($end < $start) $end->modify('+1 day');
                $interval = $start->diff($end);
                $duree_formattee = $interval->h + ($interval->days * 24) . 'h' . $interval->i . 'min';

                $extra_comment = "";
                if ($horaire) {
                    $expected_end = new \DateTime($horaire->ended_at);
                    $expected_start = new \DateTime($horaire->started_at);
                    if ($expected_end < $expected_start) $expected_end->modify('+1 day');
                    $extra_comment = ($end < $expected_end) ? " | Parti tôt." : " | Parti à l'heure.";
                } else {
                    $extra_comment = " | Sans horaire précis.";
                }

                $presence->update([
                    'ended_at' => $data['heure'],
                    'photos_fin' => $photoUrl ?? null,
                    'status_photo_fin' => $data['status_photo'] ?? null,
                    'duree' => $duree_formattee,
                    'status' => 'sortie',
                    'commentaires' => $presence->commentaires . ' - ' . $commentaire_distance . $extra_comment,
                ]);

                if($site->emails){
                    (new EmailController())->sendMail([
                        "emails" => $site->emails,
                        "title" => "Départ signalée",
                        "photo" => $photoUrl,
                        "agent" => $agent->matricule . ' - ' . $agent->fullname,
                        "site" => $site->code . ' - ' . $site->name,
                        "date" => $now->format("d/m/y H:i")
                    ]);
                }

                return response()->json([
                    "status" => "success",
                    "message" => "Présence sortie enregistrée.",
                    "result" => $presence
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }


    /**
     * Creation de la presence visite du superviseur dans les sites
     * @param Request $request HTTP REQ
     * @return JsonResponse
    */
    public function createSupervisorSiteVisit(Request $request): JsonResponse
    {
        try {
            if ($request->has('elements') && is_string($request->elements)) {
                $request->merge([
                    'elements' => json_decode($request->elements, true)
                ]);
            }
            $data = $request->validate([
                "id"=>"nullable|int|exists:presence_supervisor_sites,id",
                "matricule" => "required|string|exists:agents,matricule",
                "site_id" => "required|int|exists:sites,id",
                "schedule_id" => "required|int|exists:schedule_supervisors,id",
                "photo" => "required|file",
                "comment"=> "nullable|string",
                "latlng" => "required|string",
                "elements"=>"nullable|array",
                "elements.*.presence_id"=>"required|int|exists:presence_supervisor_sites,id",
                "elements.*.element_id"=>"required|int|exists:supervision_control_elements,id",
                "elements.*.agent_id"=>"required|int|exists:agents,id",
                "elements.*.note"=>"required|string",
            ]);


            $agent = Agent::where('matricule', $data['matricule'])->where("role", "supervisor")->first();
            if(!$agent){
                return response()->json(["errors"=>"Unauthorized"]);
            }
            $now = Carbon::now()->setTimezone('Africa/Kinshasa');
            $site = Site::find($data["site_id"]);
            // Gestion de la distance et du commentaire
            list($lat1, $lng1) = explode(",", $data["latlng"]);
            list($lat2, $lng2) = explode(",", $site->latlng);
        
            $distance = (new AppManagerController())->calculateDistance($lat1, $lng1, $lat2, $lng2);
            $photoUrl = "";
            //Capture photo agent debut
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/supervisor_visits'), $filename);
                $photoUrl = url('uploads/supervisor_visits/' . $filename);
            }
            $data["date"] = $now->toDateString();
            $data["agent_id"] = $agent->id;

            $presence = PresenceSupervisorSite::where('agent_id', $data["agent_id"])
                ->where('id', $data["id"] ?? null)
                ->first();
            if($presence){
                $data["ended_at"] = $now->format('H:i');
                $data["end_photo"] = $photoUrl;
                $data["duree"] = $this->calculateTime($presence->started_at, $data["ended_at"]);
            }
            else{
                $data["distance"] = $distance;
                $data["start_photo"] = $photoUrl;
                $data["started_at"] = $now->format('H:i');
            }
            unset($data['matricule']);
            unset($data['photo']);
            $schedule = ScheduleSupervisor::find($data["schedule_id"]);
            $scheduleDate = Carbon::parse($schedule->date);
            $submittedDate = Carbon::parse($data["date"]);

            if ($scheduleDate->gt($submittedDate)) {
                $data["status"] = "Effectué avant";
            } elseif ($scheduleDate->lt($submittedDate)) {
                $data["status"] = "Non respecté";
            } else {
                $data["status"] = "success";
            }

            $result = PresenceSupervisorSite::updateOrCreate(["id"=>$data["id"] ?? null], $data);
             $elements = isset($data["elements"]) ? $data["elements"] : [];
            if($result){
                if(isset($elements) && !empty($elements)){
                    foreach($elements as $el){
                        PresenceSupervisorControl::updateOrCreate(["element_id"=>$el["element_id"]], $el);
                    }
                }
            }

            return response()->json([
                "status"=>"success",
                "result"=> $result
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    private function calculateTime($start, $end){
        $heure1 = Carbon::createFromTimeString($start);
        $heure2 = Carbon::createFromTimeString($end);
        $diff = $heure1->diff($heure2);
        // Résultat
        $heures = $diff->h;     // heures
        $minutes = $diff->i;    // minutes
        return "{$heures}h{$minutes}m";
    }

    public function getPresencesBySiteAndDate(Request $request)
    {
        try {

            $date = $request->query("date");
            $siteId = $request->query("site_id");

            $presences = PresenceAgents::with(['agent', 'horaire'])
                ->when($siteId, function ($query, $siteId) {
                    return $query->where('site_id', $siteId);
                })
                ->when($date, function ($query, $date) {
                    return $query->whereDate('created_at', $date);
                }, function ($query) {
                    // Si aucune date n'est passée, on prend la date du jour
                    return $query->whereDate('created_at', Carbon::now());
                })
                ->orderByRaw("
                    CASE
                        WHEN retard = 'no' THEN 0
                        WHEN retard IS NULL THEN 1
                        WHEN retard = 'yes' THEN 2
                        ELSE 3
                    END
                ")
                ->orderByDesc("created_at")
                ->paginate(5);

            return response()->json([
                'status' => 'success',
                'date' => $date,
                'presences' => $presences
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }


    //Renvoie la liste de l'horaire complet
    public function getAllHoraires(Request $request){
        $all = $request->query("all") ?? null;
        $horaires = PresenceHoraire::orderByDesc("id");
        return response()->json(['horaires' => isset($all) ? $horaires->get() : $horaires->paginate(10) ]);
    }

    public function getAllGroups(Request $request){
        $all = $request->query("all") ?? null;
        $groups = AgentGroup::with("horaire")->orderByDesc("id");
        return response()->json(['groups' => isset($all) ? $groups->get() : $groups->paginate(perPage: 10) ]);
    }



    private function getDateRange(Request $request, $year)
    {
        $now = Carbon::now();

        return match ($request->period) {
            'week' => [
                'start' => Carbon::now()->startOfWeek()->toDateString(),
                'end' => Carbon::now()->endOfWeek()->toDateString(),
            ],
            'month' => [
                'start' => Carbon::now()->startOfMonth()->toDateString(),
                'end' => Carbon::now()->endOfMonth()->toDateString(),
            ],
            'quarter' => [
                'start' => Carbon::now()->firstOfQuarter()->toDateString(),
                'end' => Carbon::now()->lastOfQuarter()->toDateString(),
            ],
            'year' => [
                'start' => Carbon::create($year)->startOfYear()->toDateString(),
                'end' => Carbon::create($year)->endOfYear()->toDateString(),
            ],
            'custom' => [
                'start' => Carbon::parse($request->date_begin)->toDateString(),
                'end' => Carbon::parse($request->date_end)->toDateString(),
            ],
            default => [
                'start' => Carbon::create($year)->startOfYear()->toDateString(),
                'end' => Carbon::create($year)->endOfYear()->toDateString(),
            ],
        };
    }

    private function parseToMinutes($duree)
    {
        if (!$duree || !str_contains($duree, ':')) return 0;

        [$h, $m] = explode(':', $duree);
        return ((int)$h * 60) + (int)$m;
    }


    public function getSupervisorReport(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'nullable|exists:agents,id',
            'site_id' => 'nullable|exists:sites,id',
            'year' => 'nullable|integer',
            'period' => 'nullable|in:week,month,quarter,year,custom',
            'date_begin' => 'nullable|date|required_if:period,custom',
            'date_end' => 'nullable|date|required_if:period,custom',
        ]);

        $year = $request->input('year', now()->year);

        // Gère la période de recherche
        $range = $this->getDateRange($request, $year);

        $agents = Agent::where("role", "supervisor");

        if ($request->filled('agent_id')) {
            $agents->where('id', $request->agent_id);
        }

        $agents = $agents->get();
        $reports = [];

        foreach ($agents as $agent) {
            $scheduleIds = ScheduleSupervisor::where('agent_id', $agent->id)
                ->whereBetween('date', [$range['start'], $range['end']])
                ->pluck('id');

            $scheduledSites = ScheduleSupervisorSite::whereIn('schedule_id', $scheduleIds);

            if ($request->filled('site_id')) {
                $scheduledSites->where('site_id', $request->site_id);
            }
            $scheduledCount = $scheduledSites->count();
            $presences = PresenceSupervisorSite::where('agent_id', $agent->id)
                ->whereBetween('date', [$range['start'], $range['end']]);

            if ($request->filled('site_id')) {
                $presences->where('site_id', $request->site_id);
            }

            $presenceData = $presences->get();

            $visitedCount = $presenceData->count();
            $totalDuration = $presenceData->sum(fn($p) => $this->parseToMinutes($p->duree));
            $avgDuration = $visitedCount ? round($totalDuration / $visitedCount) : 0;
            $statusCounts = $presenceData->groupBy('status')->map->count();

            $reports[] = [
                'supervisor' => $agent->fullname,
                'matricule' => $agent->matricule,
                'scheduled_sites' => $scheduledCount,
                'visited_sites' => $visitedCount,
                'coverage' => $scheduledCount ? round(($visitedCount / $scheduledCount) * 100, 2) . '%' : '0%',
                'total_duration_minutes' => $totalDuration,
                'average_duration_minutes' => $avgDuration,
                'status_breakdown' => $statusCounts,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $reports,
        ]);
    }



}
