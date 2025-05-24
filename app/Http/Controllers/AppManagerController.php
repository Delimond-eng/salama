<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentRequest;
use App\Models\Announce;
use App\Models\Area;
use App\Models\Patrol;
use App\Models\PatrolScan;
use App\Models\Schedules;
use App\Models\Signalement;
use App\Models\Site;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppManagerController extends Controller
{
    /**
     * Start patrol tag
     * @param Request $request
     * @return JsonResponse
     */
    public function startPatrol(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                "patrol_id" => "nullable|int|exists:patrols,id",
                "site_id"   => "nullable|int|int|exists:sites,id",
                "agency_id" => "nullable|int|exists:agencies,id",
                "scan.agent_id" => "required|int|exists:agents,id",
                "scan.area_id"  => "required|int|exists:areas,id",
                "scan.comment"  => "nullable|string",
                "scan.latlng"   => "required|string",
                "scan.photo"   => "nullable|file",
                "scan.matricule"   => "nullable|string",
            ]);

            $scan = $data["scan"];
            $area = Area::find($scan['area_id']);

            // Traitement de la photo
            if ($request->hasFile('scan.photo')) {
                $file = $request->file('scan.photo');
                $filename = uniqid('patrol_') . '.' . $file->getClientOriginalExtension();
                $destination = public_path('patrols');
                $file->move($destination, $filename);

                // Générer un lien complet sans utiliser storage
                $scan['photo'] = url('patrols/' . $filename);
            }

            list($areaLat, $areaLng) = explode(',', $area->latlng ?? "0,0");
            list($scanLat, $scanLng) = explode(',', $scan['latlng']);

            $distance = $this->calculateDistance($areaLat, $areaLng, $scanLat, $scanLng);
            $tolerance = 100;

            $scan['status'] = ($distance <= $tolerance) ? "success" : "fail";
            $scan['distance'] = "{$distance} m";
            $patrolId = $data['patrol_id'];

            if ($patrolId) {
                $scan["patrol_id"] = $patrolId;
                $response = PatrolScan::updateOrCreate([
                    "patrol_id" => $scan["patrol_id"],
                    "area_id"   => $scan["area_id"]
                ], $scan);

                return response()->json([
                    "status" => "success",
                    "result" => $response
                ]);
            }

            $now = Carbon::now();
            $data["started_at"] = $now->toDateTimeString();
            $data["status"] = "pending";
            $data["agent_id"] = $scan["agent_id"];

            $patrol = Patrol::create($data);

            if ($patrol) {
                $scan["patrol_id"] = $patrol->id;
                PatrolScan::create($scan);
                $site = Site::find($data["site_id"]);
                $site->status = 'pending';
                $site->save();

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

        return response()->json([
            "errors" => "Echec de traitement de la requête !"
        ], 500);
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
                    $agencyId = $data['agency_id'];
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = "uploads/agence_" . $agencyId;
                    $file->storeAs($path, $filename, 'public');
                    $data['media'] = url("storage/$path/$filename");
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
    public function viewAllSignalements():JsonResponse
    {
        $agencyId = Auth::user()->agency_id;
        $signalements = Signalement::with("agent")
            ->with("site")
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")
            ->get();
        return response()->json([
            "status"=>"success",
            "signalements"=>$signalements
        ]);
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
            ->paginate(10);

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



    /* public function viewPatrolReports():JsonResponse{
        $agencyId = Auth::user()->agency_id ?? 1;
        $patrols = Patrol::with("agent")
            ->with("site")
            ->with("scans.agent")
            ->with("scans.area")
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")
            ->paginate(10);
        return response()->json([
            "status"=>"success",
            "patrols"=>$patrols
        ]);
    } */




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
                "comment_audio" => "nullable|file|mimes:audio/mpeg,mpga,mp3,wav",
            ]);
            // Ajout des informations de fin de patrouille
            $now = Carbon::now();
            $data["ended_at"] = $now->toDateTimeString();
            $data["status"] = "closed";
            $patrol = Patrol::find($data["patrol_id"]);
            $patrol->ended_at = $data["ended_at"];
            $patrol->comment_text = $data["comment_text"] ?? null;
            $patrol->comment_audio = $data["comment_audio"] ?? null;
            $patrol->status = $data["status"];
            $patrol->save();
            $site = Site::find($patrol->site_id);
            $site->status = 'actif';
            $site->save();
            return response()->json([
                "status" => "success",
                "result" => $patrol
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors], );
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()], );
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
                "schedules.*.libelle" => "required|string",
                "schedules.*.start_time" => "required|string",
                "schedules.*.end_time" => "nullable|string",
                "schedules.*.site_id" => "required|int|exists:sites,id",
            ]);

            $schedules = $data["schedules"];
            foreach ($schedules as $schedule){
                $schedule["agency_id"] = Auth::user()->agency_id;
                Schedules::updateOrCreate([
                    "site_id"=>$schedule["site_id"],
                    "libelle"=>$schedule["libelle"]
                ], $schedule);
            }
            return response()->json([
                "status" => "success",
                "result" => "Planning créé avec succès"
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }


    /**
     * View all schedules from admin
     * @return JsonResponse
    */
    public function viewAllSchedulesByAdmin():JsonResponse
    {
        $agencyId = Auth::user()->agency_id;
        $schedules = Schedules::with("site")
            ->where("status", "actif")
            ->where("agency_id", $agencyId)
            ->get();
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
        $agencyId = $request->query("agency_id");
        $siteId = $request->query("site_id");
        $schedules = Schedules::with("site")
            ->where("status", "actif")
            ->where("agency_id", $agencyId)
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
                ->where("password", $data["password"])->where("status", "actif")->first();
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












}
