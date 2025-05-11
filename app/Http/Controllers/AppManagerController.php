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
    public function startPatrol(Request $request) : JsonResponse
    {
        try {
            $data = $request->validate([
                "patrol_id" => "nullable|int|exists:patrols,id",
                "site_id"   => "required_if:patrol_id,null|int|exists:sites,id",
                "agency_id" => "required_if:patrol_id,null|int|exists:agencies,id",
                "scan.agent_id" => "required|int|exists:agents,id",   // Correction pour la validation des champs imbriqués
                "scan.area_id"  => "required|int|exists:areas,id",    // Correction pour la validation des champs imbriqués
                "scan.comment"  => "nullable|string",
                "scan.latlng"   => "required|string"                  // Correction pour la validation des champs imbriqués
            ]);

            $scan = $data["scan"];
            $area = Area::find($scan['area_id']);

            // Extraction des coordonnées GPS de la zone et du scan
            list($areaLat, $areaLng) = explode(':', $area->latlng ?? "8844757:30934949");
            list($scanLat, $scanLng) = explode(':', $scan['latlng']);

            // Calcul de la distance en mètres entre les deux points GPS
            $distance = $this->calculateDistance($areaLat, $areaLng, $scanLat, $scanLng);
            $tolerance = 1; // Tolérance de distance en mètres

            // Mise à jour du statut du scan en fonction de la distance
            $scan['status'] = ($distance <= $tolerance) ? "success" : "fail";
            $scan['distance'] = "{$distance} m";
            $patrolId = $data['patrol_id'];

            // Si la patrouille existe, on ajoute le scan
            if ($patrolId) {
                $scan["patrol_id"] = $data["patrol_id"];
                $response = PatrolScan::updateOrCreate([
                    "patrol_id"=>$scan["patrol_id"],
                    "area_id"=>$scan["area_id"]
                ],$scan);

                return response()->json([
                    "status" => "success",
                    "result" => $response
                ]);
            }

            // Sinon, on démarre une nouvelle patrouille
            $now = Carbon::now();
            $data["started_at"] = $now->toDateTimeString();
            $data["status"] = "pending";
            $data["agent_id"] = $scan["agent_id"];

            $patrol = Patrol::create($data);

            if ($patrol) {
                $scan["patrol_id"] = $patrol->id;
                PatrolScan::create($scan);

                return response()->json([
                    "status" => "success",
                    "result" => $patrol
                ]);
            }
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()], 400);
        }
        catch (\Illuminate\Database\QueryException $e) {
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
                "agent_id"=>"required|int|exists:agents,id",
                "agency_id"=>"required|int|exists:agencies,id",
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
                    "media" => "nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:2048",
                    "site_id" => "required|int|exists:sites,id",
                    "agent_id" => "required|int|exists:agents,id",
                    "agency_id" => "required|int|exists:agencies,id"
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
    public function viewPendingPatrols():JsonResponse{
        $agencyId = Auth::user()->agency_id ?? 1;
        $patrols = Patrol::with("site.areas")
            ->with("agent")
            ->with("scans.agent")
            ->with("scans.area")
            ->where("status", "pending")
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")
            ->get();
        return response()->json([
            "status"=>"success",
            "pending_patrols"=>$patrols
        ]);
    }


    /**
     * View all Patrol reports
     * @return JsonResponse
    */
    public function viewPatrolReports():JsonResponse{
        $agencyId = Auth::user()->agency_id ?? 1;
        $patrols = Patrol::with("agent")
            ->with("site")
            ->with("scans.agent")
            ->with("scans.area")
            ->where("status", "closed")
            ->where("agency_id", $agencyId)
            ->orderByDesc("id")
            ->get();
        return response()->json([
            "status"=>"success",
            "patrols"=>$patrols
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
                "comment_audio" => "nullable|file|mimes:audio/mpeg,mpga,mp3,wav",
            ]);

            // Gestion du fichier audio s'il est présent
            if ($request->hasFile('comment_audio')) {
                $audioFile = $request->file('comment_audio');
                $agencyId = $data['agency_id'];
                $audioPath = "uploads/agencie_{$agencyId}/audio";
                $filename = "audio_" . time() . '.' . $audioFile->getClientOriginalExtension();
                $filePath = $audioFile->storeAs($audioPath, $filename, 'public');
                $data['comment_audio'] = url("storage/{$filePath}");
            }
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
    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float|int
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
     * @return Response
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









}
