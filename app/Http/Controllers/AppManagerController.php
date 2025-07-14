<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentRequest;
use App\Models\Announce;
use App\Models\Area;
use App\Models\Cessation;
use App\Models\Conge;
use App\Models\Patrol;
use App\Models\PatrolScan;
use App\Models\Schedules;
use App\Models\ScheduleSupervisor;
use App\Models\Signalement;
use App\Models\Site;
use App\Models\SupervisionControlElement;
use App\Services\FcmService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppManagerController extends Controller
{
    /**
     * Start patrol tag
     * @param Request $request
     */
    public function startPatrol(Request $request)
    {
        try {
            $data = $request->validate([
                "patrol_id" => "nullable|int|exists:patrols,id",
                "site_id"   => "nullable|int|int|exists:sites,id",
                "agency_id" => "nullable|int|exists:agencies,id",
                "agent_id" => "required|int|exists:agents,id",
                "schedule_id" => "nullable|int|exists:schedules,id",
                "area_id"  => "required|int|exists:areas,id",
                "comment"  => "nullable|string",
                "latlng"   => "required|string",
                "matricule"   => "nullable|string",
            ]);
            $area = Area::find($data['area_id']);

            // Traitement de la photo
            if ($request->hasFile('photo') && !isset($data["patrol_id"])) {
                $file = $request->file('photo');
                $filename = uniqid('patrol_') . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/patrols');
                $file->move($destination, $filename);
                // Générer un lien complet sans utiliser storage
                $data['photo'] = url('uploads/patrols/' . $filename);
            }
            else{
                $data["photo"]="";
            }

            list($areaLat, $areaLng) = explode(',', $area->latlng ?? "0,0");
            list($scanLat, $scanLng) = explode(',', $data['latlng']);

            $distance = $this->calculateDistance($areaLat, $areaLng, $scanLat, $scanLng);
            $tolerance = 100;

            $data['status'] = ($distance <= $tolerance) ? "success" : "fail";
            $data['distance'] = "{$distance} m";
            $patrolId = $data['patrol_id'];

            if ($patrolId) {
                $data["patrol_id"] = $patrolId;
                $response = PatrolScan::updateOrCreate([
                    "patrol_id" => $data["patrol_id"],
                    "area_id"   => $data["area_id"]
                ], [
                    "time"=>Carbon::now(),
                    "latlng"=>$data["latlng"],
                    "comment"=>$data["comment"] ?? "",
                    "distance"=>$distance,
                    "agent_id"=>$data["agent_id"],
                    "patrol_id"=>$patrolId,
                    "area_id"=>$data["area_id"],
                    "photo"=>$data["photo"],
                    "matricule"=>$data["matricule"] ?? "",
                    "status"=>$data["status"]
                ]);
                return response()->json([
                    "status" => "success",
                    "result" => $response
                ]);
            }

            $now = Carbon::now()->setTimezone('Africa/Kinshasa');
            $data["started_at"] = $now->toDateTimeString();
            $data["status"] = "pending";

            $patrol = Patrol::create($data);

            if ($patrol) {
                PatrolScan::create([
                    "time"=>Carbon::now()->toDateTimeString(),
                    "latlng"=>$data["latlng"],
                    "comment"=>$data["comment"] ?? "",
                    "distance"=>$distance,
                    "agent_id"=>$data["agent_id"],
                    "patrol_id"=>$patrol->id,
                    "area_id"=>$data["area_id"],
                    "photo"=>$data["photo"],
                    "matricule"=>$data["matricule"] ?? "",
                    "status"=>$data["status"]
                ]);
                $site = Site::find($data["site_id"]);
                $site->status = 'pending';
                $site->save();
                $agent = Agent::find($patrol->agent_id);
                //Send mails not
                try{
                    if($site->emails){
                        (new EmailController())->sendMail([
                            "emails" => $site->emails,
                            "title" => "Patrouille en cours",
                            "photo" => $data["photo"],
                            "agent" => $agent->matricule . ' - ' . $agent->fullname,
                            "site" => $site->code . ' - ' . $site->name,
                            "date" => $now->format("d/m/y H:i")
                        ]);
                    }
                }
                catch(\Exception $e){
                    Log::info($e->getMessage());
                }
                //Send notification to client
                try{
                    if ($site->tokens) {
                        $fcm = new FcmService();
                        $tokens = $site->tokens;
                        $title = "Patrouille en cours";
                        $heure = Carbon::now()->format("H:i");
                        $body = "Patrouille en cours dans votre concession. Heure de début : $heure.";
                        $fcm->sendNotificationToManyTokens($tokens, $title, $body);
                    }
                }
                catch(\Exception $exception){
                    Log::info($exception->getMessage());
                }
                return response()->json([
                    "status" => "success",
                    "result" => $patrol
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()], 400);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }


    /**
     * Create announce
     * @param Request $request
     * @return JsonResponse
     */
    public function createAnnounce(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $data = $request->validate([
                "title" => "required|string",
                "content" => "required|string",
                "site_id"=>"nullable|int|exists:sites,id"
            ]);
            $data["agency_id"]= Auth::user()->agency_id;
            $response = Announce::create($data);

            if($response){
                return response()->json([
                    "status"=>"success",
                    "result"=>$response
                ]);
            }else{
                return response()->json(['errors' => 'Echec du traitement de la requête !'],);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], );
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()], );
        }
    }



    /**
     * Create requests
     * @param Request $request
     * @return JsonResponse
     */
    public function createRequest(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $data = $request->validate([
                "object" => "required|string",
                "description" => "required|string",
                "agent_id"=>"required|int",
                "agency_id"=>"required|int",
            ]);
            $response = AgentRequest::create($data);
            if($response){
                return response()->json([
                    "status"=>"success",
                    "result"=>$response
                ]);
            }else{
                return response()->json(['errors' => 'Echec du traitement de la requête !'],);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], );
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()], );
        }
    }


    /**
     * View all requests
     * @return JsonResponse
    */
    public function viewAllRequests() : JsonResponse
    {
        $agencyId = Auth::user()->agency_id;
        $requests = AgentRequest::with("agent")
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")
            ->get();
        return response()->json([
            "status"=>"success",
            "requests"=>$requests
        ]);
    }


    /**
     * Create signalement
     * @param Request $request
     * @return JsonResponse
     */
    public function createSignalement(Request $request): JsonResponse
    {
            try {
                // Validation des données
                $data = $request->validate([
                    "title" => "required|string",
                    "description" => "required|string",
                    "media" => "nullable|file",
                    "site_id" => "required|int|exists:sites,id",
                    "agent_id" => "required|int|exists:agents,id",
                    "agency_id" => "required|int"
                ]);

                // Vérifier si un fichier est fourni dans le champ 'media'
                if ($request->hasFile('media')) {
                    $file = $request->file('media');
                    $filename = uniqid('signalement_') . '.' . $file->getClientOriginalExtension();
                    $destination = public_path(path: 'uploads/signalements');
                    $file->move($destination, $filename);
                    // Générer un lien complet sans utiliser storage
                    $data['media'] = url('uploads/signalements/' . $filename);
                }
                $response = Signalement::create($data);

                if($response) {
                    return response()->json([
                        "status" => "success",
                        "result" => $response
                    ]);
                } else {
                    return response()->json(['errors' => 'Echec du traitement de la requête !']);
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                $errors = $e->validator->errors()->all();
                return response()->json(['errors' => $errors]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['errors' => $e->getMessage()]);
            }
    }



    /**
     * view all signalements
     * @return JsonResponse
    */
    public function viewAllSignalements(Request $request):JsonResponse
    {
        $date = $request->query("date");
        $siteId = $request->query("siteId");
        //$agencyId = Auth::user()->agency_id;
        $signalements = Signalement::with(["agent", "site"])
        ->when($date, function ($query, $date) {
            $query->whereDate("created_at", $date);
        })
        ->when($siteId, function ($query, $siteId) {
            $query->where("site_id", $siteId);
        })
        ->orderByDesc("id")
        ->paginate(3);
        // Ajoute le champ formattedDate à chaque signalement
        $signalements->getCollection()->transform(function ($item) {
            $item->formattedDate = $this->formatDateHumanReadable($item->created_at);
            return $item;
        });

        $sites = Site::all();
        return response()->json([
            "status"=>"success",
            "signalements"=>$signalements,
            "sites"=>$sites
        ]);
    }

    private function formatDateHumanReadable($date)
    {
        $now = Carbon::now();
        $diffInMinutes = $date->diffInMinutes($now);

        if ($diffInMinutes < 1) {
            return "Maintenant";
        } elseif ($diffInMinutes < 60) {
            return "Il y a de cela " . $diffInMinutes . " min";
        }
        $diffInHours = $date->diffInHours($now);
        if ($diffInHours < 24) {
            return "Il y a de cela " . $diffInHours . "h";
        }
        $diffInDays = $date->diffInDays($now);
        if ($diffInDays == 1) {
            return "Il y a de cela 1 jour";
        } elseif ($diffInDays <= 6) {
            return "Il y a de cela " . $diffInDays . " jours";
        } elseif ($diffInDays < 30) {
            return "Il y a de cela une semaine";
        } elseif ($diffInDays < 60) {
            return "Il y a de cela un mois";
        } else {
            return "Il y a de cela " . $date->diffInMonths($now) . " mois";
        }
    }




    /**
     * Allow to load announces from mobile by agent
     * @return JsonResponse
    */
    public function loadAnnouncesFromMobile(Request $request):JsonResponse{
        $siteId = $request->query("site_id");
        $agencyId = $request->query("agency_id");
        $announces = Announce::with("site")
                ->where("site_id", $siteId)
                ->orWhere("site_id", null)
                ->where("agency_id", $agencyId)
                ->where("status", "actif")
                ->orderByDesc("id")
                ->get();
        return response()->json([
            "status"=>"success",
            "announces"=>$announces
        ]);
    }




    /**
     * View all pending Patrol
     * @return JsonResponse
    */
    public function viewPendingPatrols(): JsonResponse
    {
        $agencyId = Auth::user()->agency_id ?? 1;

        $patrols = Patrol::with("site.areas")
            ->with("agent")
            ->with("scans.agent")
            ->with("scans.area")
            ->where("status", "pending")
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")
            ->get();

        // Traitement de chaque patrol
        $patrols = $patrols->map(function ($patrol) {
            $scannedAreaIds = $patrol->scans->pluck('area_id')->toArray();

            $mapDatas = $patrol->site->areas->map(function ($area) use ($scannedAreaIds) {
                return [
                    'id' => $area->id,
                    'libelle' => $area->libelle,
                    'latlng' => $area->latlng,
                    'scan_status' => in_array($area->id, $scannedAreaIds) ? 'scanned' : 'none',
                ];
            });

            // Ajout de la clé map_datas à l'objet patrol
            $patrol->map_datas = $mapDatas;

            return $patrol;
        });

        return response()->json([
            "status" => "success",
            "pending_patrols" => $patrols
        ]);
    }


    /**
     * View all Patrol reports
     * @return JsonResponse
    */
    public function viewPatrolReports(): JsonResponse
    {
        $agencyId = Auth::user()->agency_id ?? 1;

        $patrols = Patrol::with(["agent", "site", "scans.agent", "scans.area"])
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")
            ->paginate(perPage: 10);

        $patrols->getCollection()->transform(function ($patrol) {
            // 1. Durée réelle de la patrouille
            $start = $patrol->started_at
                ? Carbon::parse($patrol->started_at)
                : ($patrol->scans->first()?->time ? Carbon::parse($patrol->scans->first()->time) : null);

            $end = $patrol->ended_at ? Carbon::parse($patrol->ended_at) : Carbon::now();
            $durationMinutes = $start ? $start->diffInMinutes($end) : null;

            // 2. Zones scannées vs attendues
            $scannedZoneIds = $patrol->scans->pluck('area_id')->unique();
            $zonesScanned = $scannedZoneIds->count();
            $zonesExpected = Area::where("site_id", $patrol->site_id)->count();
            $coverageRate = $zonesExpected > 0 ? round(($zonesScanned / $zonesExpected) * 100, 2) : 0;

            // 3. Estimation du périmètre basé sur les areas du site
            $areas = Area::where("site_id", $patrol->site_id)->get();
            $totalDistance = 0;

            for ($i = 0; $i < count($areas); $i++) {
                $j = ($i + 1) % count($areas); // fermeture du périmètre

                if (!empty($areas[$i]->latlng) && !empty($areas[$j]->latlng)) {
                    $latlng1 = explode(',', $areas[$i]->latlng);
                    $latlng2 = explode(',', $areas[$j]->latlng);

                    if (count($latlng1) === 2 && count($latlng2) === 2) {
                        [$lat1, $lng1] = $latlng1;
                        [$lat2, $lng2] = $latlng2;
                        $totalDistance += $this->calculateDistance($lat1, $lng1, $lat2, $lng2);
                    }
                }
            }

            // 4. Durée estimée théorique (en minutes) pour parcourir le périmètre à 1.11 m/s
            $estimatedDurationMinutes = $totalDistance > 0 ? ($totalDistance / 1.11 / 60) : 0;

            // 5. Comparaison et efficacité
            $efficiency = null;
            $efficiency_label = null;

            if ($durationMinutes && $estimatedDurationMinutes > 0) {
                $efficiency = round(max(min(($estimatedDurationMinutes / $durationMinutes) * 100, 100), 0), 2);

                if ($efficiency >= 90) {
                    $efficiency_label = "Rapide";
                } elseif ($efficiency >= 70) {
                    $efficiency_label = "Correct";
                } elseif ($efficiency >= 40) {
                    $efficiency_label = "Lent";
                } else {
                    $efficiency_label = "Très lent";
                }
            }

            // 6. Statistiques par scan avec durée entre scans
            $scans = $patrol->scans->sortBy('time')->values();
            $scansStats = $scans->map(function ($scan, $index) use ($scans) {
                $distance = 0;
                if (!empty($scan->latlng) && !empty($scan->area?->latlng)) {
                    $latlng1 = explode(',', $scan->latlng);
                    $latlng2 = explode(',', $scan->area->latlng);

                    if (count($latlng1) === 2 && count($latlng2) === 2) {
                        [$lat1, $lng1] = $latlng1;
                        [$lat2, $lng2] = $latlng2;
                        $distance = $this->calculateDistance($lat1, $lng1, $lat2, $lng2);
                    }
                }

                $time = Carbon::parse($scan->time, tz:"Africa/Kinshasa");
                $durationSincePrevious = 0;

                if ($index > 0) {
                    $previousTime = Carbon::parse($scans[$index - 1]->time);
                    $durationSincePrevious = $previousTime->diffInSeconds($time);
                }

                return [
                    "area" => $scan->area?->libelle ?? 'Inconnu',
                    "time" => $time->format('H:i'),
                    "distance_meters" => round($distance, 2),
                    "duration_since_previous_seconds" => $durationSincePrevious,
                ];
            });

            // Enrichissement de l'objet
            $patrol->duration_minutes = $durationMinutes;
            $patrol->zones_scanned = $zonesScanned;
            $patrol->zones_expected = $zonesExpected;
            $patrol->coverage_rate = $coverageRate;
            $patrol->estimated_duration_minutes = round($estimatedDurationMinutes, 2);
            $patrol->total_distance_meters = round($totalDistance, 2);
            $patrol->efficiency_score = $efficiency ?? 0;
            $patrol->efficiency_label = $efficiency_label ?? "";
            $patrol->scans_stats = $scansStats;

            return $patrol;
        });

        return response()->json([
            "status" => "success",
            "patrols" => $patrols
        ]);
    }



    /**
     * Close Patrol Tag
     * @param Request $request
     * @return JsonResponse
     */
    public function closePatrolTag(Request $request) : JsonResponse
    {
        try {
            // Validation des données
            $data = $request->validate([
                "patrol_id" => "required|int|exists:patrols,id",
                "comment_text" => "nullable|string",
                "comment_audio" => "nullable|file|mimes:audio/mpeg,mpga,mp3,wav"
            ]);

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/patrols'), $filename);
                $data["photo"] = url('uploads/patrols/' . $filename);
            } else {
                $data["photo"] = "";
            }

            // Ajout des informations de fin de patrouille
            $now = Carbon::now()->setTimezone('Africa/Kinshasa');
            $data["ended_at"] = $now;
            $data["status"] = "closed";

            $patrol = Patrol::find($data["patrol_id"]);
            $patrol->ended_at = $data["ended_at"];
            $patrol->comment_text = $data["comment_text"] ?? null;
            $patrol->comment_audio = $data["comment_audio"] ?? null;
            $patrol->status = $data["status"];
            $patrol->photo = $data["photo"];
            $patrol->save();

            $site = Site::find($patrol->site_id);
            $site->status = 'actif';
            $site->save();

            $agent = Agent::find($patrol->agent_id);

            $this->updateScheduleStatusFromPatrol($patrol);

            if ($site->emails) {
                try{
                    (new EmailController())->sendMail([
                        "emails" => $site->emails,
                        "title" => "Patrouille en cours",
                        "photo" => $data["photo"],
                        "agent" => $agent->matricule . ' - ' . $agent->fullname,
                        "site" => $site->code . ' - ' . $site->name,
                        "date" => $now->format("d/m/y H:i")
                    ]);
                }
                catch(\Exception $e){
                    Log::info($e->getMessage());
                }
            }

            try{
                if ($site->tokens) {
                    $tokens = $site->tokens;
                    $fcm = new FcmService();
                    $title = "Fin de la patrouille";
                    $heure = $patrol->started_at->format("H:i");
                    $heureFin = $patrol->ended_at->format("H:i");
                    $body = "Fin de la patrouille dans votre concession. Début : $heure - Fin : $heureFin.";
                    $fcm->sendNotificationToManyTokens($tokens, $title, $body);
                }
            }
            catch(\Exception $exception){
                Log::info($exception->getMessage());
            }
            return response()->json([
                "status" => "success",
                "result" => $patrol
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    /**
     * GET PENDING STATUS PATROL BY SITE
     * @return JsonResponse
    */
    public function getPendingPatrol(Request $request){
        $id = $request->query("id") ?? "";
        $patrol = Patrol::where("site_id", $id)->whereNull("ended_at")->get();

        return response()->json([
            "status"=>"success",
            "patrol"=>$patrol
        ]);
    }


    private function updateScheduleStatusFromPatrol(Patrol $patrol)
    {
        $patrolStart = Carbon::parse($patrol->started_at)->setTimezone('Africa/Kinshasa');
        $toleranceMinutes = 15;

        $schedule = Schedules::where('site_id', $patrol->site_id)
            ->whereDate('date', $patrolStart->toDateString())
            ->first();

        if (!$schedule) {
            Log::info("Aucun planning trouvé pour la patrouille ID {$patrol->id}");
            return;
        }

        // Ne pas recalculer si planning déjà marqué comme 'fail'
        if (in_array($schedule->status, ['success', 'fail', 'partial'])) {
            Log::info("Planning #{$schedule->id} déjà traité avec statut {$schedule->status}.");
            return;
        }

        try {
            $start = $this->parseDateTime($schedule->date, $schedule->start_time);
            $end = $schedule->end_time
                ? $this->parseDateTime($schedule->date, $schedule->end_time)
                : now('Africa/Kinshasa');

            $toleranceStart = $start->copy()->subMinutes($toleranceMinutes);
            $toleranceEnd = $end->copy()->addMinutes($toleranceMinutes);

            Log::info("Mise à jour via patrouille #{$patrol->id} → planning #{$schedule->id}", [
                'patrolStart' => $patrolStart,
                'start' => $start,
                'end' => $end,
                'toleranceStart' => $toleranceStart,
                'toleranceEnd' => $toleranceEnd,
            ]);

            if ($patrolStart->lt($start) || $patrolStart->gt($end)) {
                if (now('Africa/Kinshasa')->gt($toleranceEnd)) {
                    Log::warning("Patrouille hors créneau strict.");
                    $this->sendFailureEmail($schedule, $patrol->agent ?? null, $patrol->photo ?? null, $patrolStart);
                    $schedule->status = 'fail';
                    $schedule->save();
                }
                return;
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
                $this->sendFailureEmail($schedule, $patrol->agent ?? null, $patrol->photo ?? null, $patrolStart);
            }

            if ($schedule->status !== 'fail') {
                $schedule->status = $newStatus;
                $schedule->save();
                Log::info("Planning #{$schedule->id} mis à jour en statut : {$newStatus}");
            }

        } catch (\Exception $e) {
            Log::error("Erreur de mise à jour du planning #{$schedule->id} via patrouille #{$patrol->id} : " . $e->getMessage());
        }
    }


    //Formatte bien la date et l'heure
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


    //Fonction pour envoyer un mail en cas d'une patrouille manquée
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
    

    /**
     * Calcul de la distance entre deux points GPS en mètres
     * Utilisation de la formule de Haversine
    */
    public function calculateDistance($lat1, $lng1, $lat2, $lng2): float|int
    {
        $earthRadius = 6371000; // Rayon de la Terre en mètres
        // Conversion des degrés en radians
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // Différence des latitudes et longitudes
        $latDiff = $lat2 - $lat1;
        $lngDiff = $lng2 - $lng1;

        // Application de la formule de Haversine
        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($lat1) * cos($lat2) *
            sin($lngDiff / 2) * sin($lngDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        // Calcul de la distance
        $distance = $earthRadius * $c;
        return round($distance); // Distance en mètres
    }



    /**
     * Allow to delete some data
     * It change the status of tuple to deleted
    */
    public function triggerDelete(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'table'=>'required|string',
                'id'=>'required|int'
            ]);

            $result = DB::table($data['table'])
                ->where('id', $data['id'])
                ->delete();
            return response()->json([
                "status"=>"success",
                "result"=>$result
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


    /**
     * Test generate PDF
     * @param int $siteId
     * @return mixed
     */
    public function generatePdfWithQRCodes(int $siteId)
    {
        // Récupérer les zones actives pour un site donné
        $areas = Area::where("site_id", $siteId)
                    ->where("status", "actif")
                    ->get(); // Utilisez get() pour exécuter la requête et récupérer les données

        // Vérifier si des zones existent pour éviter de générer un PDF vide
        $data = ['areas' => $areas];

        // Générer le PDF avec la vue Blade
        $pdf = Pdf::loadView('pdf.invoice', $data)
                ->setPaper('a4')
                ->setOption('margin-top', 10);

        // Télécharger le fichier PDF
        return $pdf->download('areas_qrcodes_printing_'.$siteId.'.pdf');
    }


    /**
     * Create planning
     * @param Request $request
     * @return JsonResponse
    */
    public function createPlanning(Request $request) : JsonResponse
    {
        try {
            // Validation des données
            $data = $request->validate([
                "schedule.libelle" => "required|string",
                "schedule.start_time" => "required|string",
                "schedule.end_time" => "nullable|string",
                "schedule.date" => "nullable|date",
                "schedule.site_id" => "required|int|exists:sites,id",
            ]);

            $schedule = $data["schedule"];
            $schedule["agency_id"] = Auth::user()->agency_id;

            $site = Site::find($schedule["site_id"]);

            Schedules::updateOrCreate([
                "id"=>$request->id
            ], $schedule);

            try{
                if ($site->fcm_token) {
                    $fcm = new FcmService();
                    $title = "Nouvelle Patrouille programmée";
                    Carbon::setLocale('fr');
                    $date = Carbon::parse($schedule['date']);
                    $start = $schedule['start_time'];
                    $end = $schedule['end_time'];

                    $today = Carbon::today();
                    $tomorrow = Carbon::tomorrow();

                    if ($date->isSameDay($today)) {
                        $formattedDate = "aujourd'hui";
                    } elseif ($date->isSameDay($tomorrow)) {
                        $formattedDate = "demain";
                    } else {
                        // Exemple : Jeudi 10 avril 2025
                        $formattedDate = $date->translatedFormat('l j F Y');
                    }

                    $body = "Vous avez une nouvelle patrouille $formattedDate de $start à $end.";
                    $fcm->sendNotification($site->fcm_token, $title, $body);
                }
            }
            catch(\Exception $exception){
                Log::info($exception->getMessage());
            }
            
            return response()->json([
                "status" => "success",
                "result" => $schedule,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    /**
     * Create planning for supervisor
     * @param Request $request
     * @return JsonResponse
    */
    public function createSupervisorPlanning(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string',
                'date' => 'required|date|after_or_equal:today',
                'agent_id' => 'required|exists:agents,id',
                'sites' => 'required|array|min:1',
                'sites.*.site_id' => 'required|exists:sites,id',
                'sites.*.order' => 'nullable|integer',
            ]);

            // Créer le planning superviseur
            $schedule = ScheduleSupervisor::updateOrCreate([
                "id"=>$request->id ?? null
            ],[
                'title' => $data['title'],
                'date' => $data['date'],
                'status' =>  "pending",
                'comment' => $data['comment'] ?? null,
                'agent_id' => $data['agent_id'],
                'user_id' => Auth::id(),
            ]);
            // Ajouter les sites avec horaires spécifiques
            foreach ($data['sites'] as $siteData) {
                $schedule->sites()->updateOrCreate([
                    'site_id' => $siteData['site_id'],
                ],[
                    'site_id' => $siteData['site_id'],
                    'order' => $siteData['order'] ?? 1,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'result' => $schedule->load('sites.site')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }


    /**
     * Create automatic planni
     * @param Request $request
     * @return JsonResponse
    */

    public function autoCreateNightPlannings(): void
    {
        try {
            $sites = Site::all();
            $agency_id = Auth::user()->agency_id ?? 1; // à adapter si tu fais un appel via Scheduler
            $startHour = Carbon::today()->addHours(21)->addMinutes(0); // 21h00
            $interval = 2; // heure entre les patrouilles
            $pause = 0;
            $numberOfPlannings = 5;
            $baseDate = Carbon::today()->setTimezone("Africa/Kinshasa");
            $date = $baseDate->copy();
            $dateShifted = false; // pour ne pas cumuler plusieurs fois

            for ($i = 0; $i < $numberOfPlannings; $i++) {
                $start = (clone $startHour)->addHours(($interval + $pause) * $i);
                $end = (clone $start)->addHour(); // durée : 1h
                // Vérifie si l'heure dépasse minuit une seule fois
                if (!$dateShifted && $start->format('H:i') === '01:00') {
                    Log::info($start->format('H:i'));
                    $date->addDay();
                    $dateShifted = true;
                }

                $startTime = $start->format('H:i');
                $endTime = $end->format('H:i');

                foreach ($sites as $site) {
                    $exists = Schedules::where('site_id', $site->id)
                        ->where('date', $date->toDateString())
                        ->where('start_time', $startTime)
                        ->exists();

                    if (!$exists) {
                        Schedules::create([
                            'libelle'   => "Patrouille de {$startTime}",
                            'start_time'=> $startTime,
                            'end_time'  => $endTime,
                            'date'      => $date->toDateString(),
                            'site_id'   => $site->id,
                            'agency_id' => $agency_id,
                        ]);
                    }
                }
            }
            try{
                $fcm = new FcmService();
                foreach($sites as $site){
                    if($site->fcm_token){
                        $fcm->sendNotification(
                            $site->fcm_token,
                            "Patrouille automatique programmée",
                            "Les patrouilles nocturnes ont été planifiées automatiquement. Veuillez consulter votre planning pour plus de détails."
                        );
                    }
                }
            }catch(\Exception $e){
                Log::error($e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error("Erreur création automatique des plannings : " . $e->getMessage());
        }
    }


    /**
     * View all schedules for supervisor
     * @return JsonResponse
    */
    public function viewSupervisorSchedules(Request $request)
    {
        $schedules = ScheduleSupervisor::with(['agent', 'user', 'sites.site', 'presences.agent', 'presences.site', 'presences.schedule'])->orderByDesc("id")->paginate(10);
        return response()->json([
            "status"=>"success",
            "schedules"=>$schedules
        ]);
    }

    public function getSupervisorSchedulesReport(Request $request){
        $now = Carbon::today()->setTimezone("Africa/Kinshasa");
        $query=ScheduleSupervisor::with(["agent","sites.site", "presences.site", "presences.agent", "presences.elements.agent", "presences.elements.element"])
            ->whereDate("date","<",  $now)
            ->orderByDesc("id");
        $reports = $query->paginate(10);
        
        return response()->json([
            "status"=> "success",
            "reports"=> $reports
        ]);
    } 

    /**
     * View all schedules from admin
     * @return JsonResponse
    */
    public function viewAllSchedulesByAdmin(Request $request):JsonResponse
    {
        $agencyId = Auth::user()->agency_id;
        $date = $request->query("date") ?? null;
        $req = Schedules::with("site")->with(["patrol.site", "patrol.agent", "patrol.scans.area"]);
        if(isset($date)){
            $req->whereDate("date", $date);
        } 
        $schedules = $req
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")->paginate(8);
        return response()->json([
            "status"=>"success",
            "schedules"=>$schedules
        ]);
    }


    /**
     * View all Schedules for app agent guard
     * @param Request $request
     * @return JsonResponse
    */
    public function viewAllSchedulesByApp(Request $request):JsonResponse
    {
        $siteId = $request->query("site_id");
        $schedules = Schedules::with("site")
            ->where("status", "actif")
            ->where("site_id", $siteId)
            ->get();
        return response()->json([
            "status"=>"success",
            "schedules"=>$schedules
        ]);
    }


    /**
     * Login agent
     * @param Request $request
     * @return JsonResponse
    */
    public function loginAgent(Request $request) {
        try {
            // Validation des données
            $data = $request->validate([
                "matricule" => "required|string",
                "password" => "required|string",
            ]);

            $agent = Agent::with("site")->where("matricule", $data["matricule"])
                ->where("password", $data["password"])->first();
            if($agent){
                return response()->json([
                    "status"=>"success",
                    "agent"=>$agent
                ]);
            }else{
                return response()->json(['errors' => 'Matricule ou mot de passe erroné !'], );
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], );
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()], );
        }
    }

    /**
     * Login agent
     * @param Request $request
     * @return JsonResponse
    */
    public function saveMessagingToken(Request $request) {
        try {
            // Validation des données
            $data = $request->validate([
                "site_id" => "required|int|exists:sites,id",
                "fcm_token" => "required|string",
            ]);
            $site = Site::find($data["site_id"]);
            if($site){
                $site->fcm_token = $data["fcm_token"];
                $site->save();
                return response()->json([
                    "status"=>"success",
                    "result"=>$site
                ]);
            }
            else{
                 return response()->json([
                    "errors"=>"Echec de traitement des données"
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], );
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()], );
        }
    }

    public function generatePatrolPdfReport()
    {
        $agencyId = Auth::user()->agency_id ?? 1;

        $patrols = Patrol::with(['agent', 'site', 'scans.agent', 'scans.area'])
            ->where('agency_id', $agencyId)
            ->orderByDesc('id')
            ->get();

        $patrols->transform(function ($patrol) {
            // 1. Durée réelle de la patrouille
            $start = $patrol->started_at ? Carbon::parse($patrol->started_at) : null;
            $end = $patrol->ended_at ? Carbon::parse($patrol->ended_at) : now();
            $durationMinutes = $start ? $start->diffInMinutes($end) : null;

            // 2. Zones scannées vs attendues
            $scannedZoneIds = $patrol->scans->pluck('area_id')->unique();
            $zonesScanned = $scannedZoneIds->count();
            $zonesExpected = Area::where("site_id", $patrol->site_id)->count();
            $coverageRate = $zonesExpected > 0 ? round(($zonesScanned / $zonesExpected) * 100, 2) : 0;

            // 3. Estimation du périmètre basé sur les areas du site
            $areas = Area::where("site_id", $patrol->site_id)->get();
            $totalDistance = 0;

            for ($i = 0; $i < count($areas); $i++) {
                $j = ($i + 1) % count($areas); // fermeture du périmètre
                [$lat1, $lng1] = explode(',', $areas[$i]->latlng);
                [$lat2, $lng2] = explode(',', $areas[$j]->latlng);
                $totalDistance += $this->calculateDistance($lat1, $lng1, $lat2, $lng2);
            }

            // 4. Durée estimée théorique (en minutes) pour parcourir le périmètre à 1.11 m/s
            $estimatedDurationMinutes = $totalDistance / 1.11 / 60;

            // 5. Comparaison et efficacité
            $efficiency = null;
            if ($durationMinutes && $estimatedDurationMinutes > 0) {
                $efficiency = round(($estimatedDurationMinutes / $durationMinutes) * 100, 2);
            }

            // 6. Statistiques par scan avec durée entre scans
            $scans = $patrol->scans->sortBy('time')->values();
            $scansStats = $scans->map(function ($scan, $index) use ($scans) {
                [$lat1, $lng1] = explode(",", $scan->latlng);
                [$lat2, $lng2] = explode(",", $scan->area->latlng);
                $distance = $this->calculateDistance($lat1, $lng1, $lat2, $lng2);

                $time = Carbon::parse($scan->time);

                $durationSincePrevious = 0;
                
                if ($index > 0) {
                    $previousTime = Carbon::parse($scans[$index - 1]->time);
                    $durationSincePrevious = $previousTime->diffInSeconds($time);
                }

                return [
                    "area" => $scan->area->libelle,
                    "time" => $time->format('H:i'),
                    "distance_meters" => $distance,
                    "duration_since_previous_seconds" => $durationSincePrevious,
                ];
            });

            // Enrichissement de l'objet
            $patrol->duration_minutes = $durationMinutes;
            $patrol->zones_scanned = $zonesScanned;
            $patrol->zones_expected = $zonesExpected;
            $patrol->coverage_rate = $coverageRate;
            $patrol->estimated_duration_minutes = round($estimatedDurationMinutes, 2);
            $patrol->total_distance_meters = round($totalDistance, 2);
            $patrol->efficiency_score = $efficiency;
            $patrol->scans_stats = $scansStats;
            return $patrol;
        });
        $pdf = PDF::loadView('pdf.reports.patrols', ['patrols' => $patrols]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('rapport-patrouilles.pdf');
    }

    /**
     * Renvoie les données du superviseur pour la supervision des postes via le mobile
     * @return JsonResponse
    */
    public function getSupervisorDatas(Request $request)
    {
        $supervisorId = $request->query("_id");

        if ($supervisorId) {
            $elements = SupervisionControlElement::orderBy("libelle")->get();

            // Chargement du planning avec les sites
            $plannings = ScheduleSupervisor::with(["sites.site", "presences"])
                ->orderByDesc("date")
                ->where("agent_id", $supervisorId)
                ->whereDate("date", ">=", Carbon::now())
                ->get();

            // Nouvelle liste plate des sites
            $siteList = [];

            foreach ($plannings as $planning) {
                foreach ($planning->sites as $site) {
                    $siteList[] = [
                        "site_id"       => $site->site_id,
                        "site_code"       => $site->site->code,
                        "site_liblle"       => $site->site->name,
                        "site_planning_id" => $site->id,
                        "planning_id"   => $planning->id,
                        "planning_title"=> $planning->title,
                        "planning_date" => $planning->date->format("d/m/Y"),
                        "status"        => $site->status,
                        "order"         => $site->order,
                        "agent_id"      => $planning->agent_id,
                        "agents" => $this->getSiteAgent($site->site_id)
                    ];
                }
            }

            return response()->json([
                "datas" => [
                    "elements" => $elements,
                    "sites" => $siteList,
                ]
            ]);
        }

        return response()->json([
            "datas" => []
        ]);
    }

    private function getSiteAgent($siteId){
        $agents = Agent::orderBy("fullname")->where("site_id", $siteId)->get();
        return $agents;
    }


    /**
     * Gestion des congés
     * Lionnel nawej
     * @param Request $request
     * @return JsonResponse
    */
    public function createCongeAgent(Request $request): JsonResponse{
        try {
            // Validation des données reçues
            $data = $request->validate([
                'agent_id'   => 'required|exists:agents,id',
                'type'       => 'required|string',
                'date_debut' => 'required|date',
                'date_fin'   => 'required|date|after_or_equal:date_debut',
                'motif'      => 'nullable|string'
            ]);

            $agentId = $data['agent_id'];
            $debut = Carbon::parse($data['date_debut']);
            $fin = Carbon::parse($data['date_fin']);

            // 1. Vérifie si une cessation existe déjà
            $cessation = DB::table('cessations')
                ->where('agent_id', $agentId)
                ->first();

            if ($cessation) {
                return response()->json([
                    'errors' => ["L'agent est en cessation d'activités depuis le " . Carbon::parse($cessation->date)->format('d/m/Y')]
                ]);
            }

            // 2. Vérifie s’il y a un congé qui chevauche la période
            $conflitConge = DB::table('conges')
                ->where('agent_id', $agentId)
                ->where(function ($query) use ($debut, $fin) {
                    $query->whereBetween('date_debut', [$debut, $fin])
                        ->orWhereBetween('date_fin', [$debut, $fin])
                        ->orWhere(function ($query) use ($debut, $fin) {
                            $query->where('date_debut', '<=', $debut)
                                    ->where('date_fin', '>=', $fin);
                        });
                })
                ->first();

            if ($conflitConge) {
                return response()->json([
                    'errors' => ["L'agent a déjà un congé du " .
                        Carbon::parse($conflitConge->date_debut)->format('d/m/Y') .
                        " au " .
                        Carbon::parse($conflitConge->date_fin)->format('d/m/Y')]
                ]);
            }

            // Enregistrement
            $record = Conge::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            return response()->json([
                'status' => 'success',
                'result' => $record
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Une erreur est survenue.']);
        }
    }


    /**
     * Gestion des congés
     * Lionnel nawej
     * @param Request $request
     * @return JsonResponse
    */
    public function createCessationAgent(Request $request): JsonResponse{
        try {
            $data = $request->validate([
                'agent_id' => 'required|exists:agents,id',
                'type'     => 'required|string',
                'date'     => 'required|date',
                'cause'    => 'nullable|string'
            ]);

            $agentId = $data['agent_id'];
            $date = Carbon::parse($data['date']);

            // 1. Vérifie si une cessation existe déjà
            $existing = DB::table('cessations')
                ->where('agent_id', $agentId)
                ->first();

            if ($existing) {
                return response()->json([
                    'errors' => ["L'agent est déjà en cessation depuis le " . Carbon::parse($existing->date)->format('d/m/Y')]
                ]);
            }

            // 2. Vérifie si un congé couvre cette date
            $conge = DB::table('conges')
                ->where('agent_id', $agentId)
                ->where('date_debut', '<=', $date)
                ->where('date_fin', '>=', $date)
                ->first();

           /* if ($conge) {
                return response()->json([
                    'errors' => ["Impossible d'enregistrer la cessation pendant un congé actif (du " .
                        Carbon::parse($conge->date_debut)->format('d/m/Y') . " au " .
                        Carbon::parse($conge->date_fin)->format('d/m/Y') . ")"]
                ]);
            }
            */
            $record = Cessation::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            return response()->json([
                'status' => 'success',
                'result' => $record
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Une erreur est survenue.']);
        }
    }
    public function getCongesByAgent(Request $request){
        $agentId = $request->query('id'); // récupération de l'ID en paramètre GET

        $congesQuery = Conge::with("agent");

        if ($agentId) {
            $congesQuery->where('agent_id', $agentId);
        }

        $conges = $congesQuery->paginate(10);

        // Ajouter le nom complet à chaque congé
        /* $conges->transform(function ($conge) {
            $agent = $conge->agent;
            $conge->fullname = " {$agent->fullname}";
            return $conge;
        }); */

        return response()->json([
            'status' => 'success',
            'conges' => $conges
        ]);
    }
    public function getCessationsByAgent(Request $request){
        $agentId = $request->query('id'); // récupération de l'ID en paramètre GET

        $cessationsQuery = Cessation::with("agent");

        if ($agentId) {
            $cessationsQuery->where('agent_id', $agentId);
        }

        $cessations = $cessationsQuery->paginate(10);

        // Ajouter le nom complet à chaque congé
        /* $cessations->transform(function ($cessation) {
            $agent = $cessation->agent;
            $cessation->fullname = "{$agent->matricule} {$agent->fullname}";
            return $cessation;
        }); */

        return response()->json([
            'status' => 'success',
            'cessations' => $cessations
        ]);
    }
}
