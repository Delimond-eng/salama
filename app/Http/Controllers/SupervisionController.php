<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Site;
use App\Models\Agent;
use App\Models\Supervision;
use App\Models\SupervisionControlElement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisionController extends Controller
{


    public function showStationAgents(Request $request){
        $siteId = $request->query("id");
        $agents = Agent::where("role", "guard")
                ->where("site_id", $siteId)
                ->orderBy("fullname", "ASC")
                ->get();
        return response()->json([
            "status"=>"success",
            "agents"=>$agents
        ]);
    }


    /**
     * Start new supervision
     * @param Request $request
     */
    public function start(Request $request)
    {
        try {
            $data = $request->validate([
                "site_id"   => "required|int|exists:sites,id",
                "matricule" => "required|string|exists:agents,matricule",
                "latlng"   => "required|string", // position du superviseur
                "comment"  => "nullable|string",
            ]);
            $site = Site::find($data["site_id"]);

            $agent = Agent::where("matricule", $data["matricule"])->where("role", "supervisor")->first();
            if(!$agent){
                return response()->json(['errors' => "agent non autorisé à effectuer cette action."]);
            }
            $data["supervisor_id"] = $agent->id;
            // --- Gestion de la photo ---
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = uniqid('supervision_') . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/supervisions');
                $file->move($destination, $filename);
                $data['photo_debut'] = url('uploads/supervisions/' . $filename);
            }
            // --- Calcul de la distance entre la position du superviseur et celle du site ---
            list($siteLat, $siteLng) = explode(',', $site->latlng ?? "0,0");
            list($scanLat, $scanLng) = explode(',', $data['latlng']);
            $distance = $this->calculateDistance($siteLat, $siteLng, $scanLat, $scanLng);
            $tolerance = 100; // en mètres
            $status = ($distance <= $tolerance) ? "success" : "fail";

            // --- Création de la supervision ---
            $data["started_at"] = Carbon::now(tz: "Africa/Kinshasa");
            $data["general_comment"] = $data["comment"] ?? null;
            $data["distance"] = $distance;

            $supervision = Supervision::create($data);

            $this->pushNotification([
                "type"=>"arrivée",
                "nom"=>$agent->fullname,
                "matricule"=>$agent->matricule,
                "station"=>$site->name,
                "photo"=>$data['photo_debut']  ?? null,
            ], "sup");

            return response()->json([
                "status" => "success",
                "message" => "Supervision démarrée avec succès",
                "result" => [
                    "supervision" => $supervision,
                    "site" => $site,
                    "distance" => "{$distance} m",
                    "status" => $status
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }


    /**
     * Close Existing supervision
     * @param Request $request
     */
    public function close(Request $request)
    {
        try {
            $data = $request->validate([
                'supervision_id' => 'required|exists:supervisions,id',
                'comment' => 'nullable|string',
                'agents' => 'array|required'
            ]);

            $supervision = Supervision::findOrFail($data['supervision_id']);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = uniqid('supervision_') . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/supervisions');
                $file->move($destination, $filename);
                $data['photo_fin'] = url('uploads/supervisions/' . $filename);
            }

            DB::transaction(function() use ($supervision, $data, $request) {
                $supervision->update([
                    'ended_at' => Carbon::now(tz: "Africa/Kinshasa"),
                    'photo_fin' => $data['photo_fin'] ?? null,
                    'general_comment' => $data['comment'] ?? $supervision->general_comment,
                ]);

                foreach ($data['agents'] as $index => $agentData) {
                    if ($request->hasFile("agents.$index.photo")) {
                        $file = $request->file("agents.$index.photo");
                        $filename = uniqid('agent_') . '.' . $file->getClientOriginalExtension();
                        $destination = public_path('uploads/supervisions/agents');
                        $file->move($destination, $filename);
                        $agentData['photo'] = url('uploads/supervisions/agents/' . $filename);
                    }
                    $supervisionAgent = $supervision->agents()->create([
                        'agent_id' => $agentData['agent_id'],
                        'photo' => $agentData['photo'] ?? null,
                        'comment' => $agentData['comment'] ?? null,
                    ]);

                    foreach ($agentData['notes'] as $noteData) {
                        $supervisionAgent->notes()->create([
                            'control_element_id' => $noteData['control_element_id'],
                            'note' => $noteData['note'],
                            'comment' => $noteData['comment'] ?? null,
                        ]);
                    }
                }
            });

            $this->pushNotification([
                "type"=>"depart",
                "nom"=>$supervision->supervisor->fullname,
                "matricule"=>$supervision->supervisor->matricule,
                "station"=>$supervision->site->name,
                "photo"=>$data['photo_fin']  ?? null,
            ], "sup");
            return response()->json([
                'message' => 'Supervision clôturée avec succès',
                'result'=> $supervision
            ]);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()], 400);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }


    /**
     * All supervision reports
     * @param Request $request
     */
    public function reports(Request $request){
        $date = $request->query("date");
        $siteId = $request->query("site");
        $query = Supervision::with([
            'supervisor',
            'site',
            'agents.agent',
            'agents.notes.element',
            'patrols.scans'
        ]);

        if ($date) {
            $query->whereDate('created_at', $date);
        }
        if ($siteId) {
            $query->where('site_id', $siteId);
        }
        if ($request->has('supervisor_id')) {
            $query->where('supervisor_id', $request->supervisor_id);
        }
        
        $results = $query->orderBy('started_at', 'desc')->paginate(10);

        return response()->json([
            "status" => "success",
            "rondes" => $results
        ]);
    }


    public function pushNotification($data, $cat = null)
    {
        $notify = Notification::create([
            'type' => $data["type"],
            'nom_superviseur' => $data["nom"],
            'matricule' => $data["matricule"],
            'category' => $cat,
            'station' => $data["station"],
            'photo' => $data["photo"],
            'heure_action' => Carbon::now('Africa/Kinshasa'),
        ]);

        // Lier à tous les utilisateurs (ou à un certain rôle)
        $userIds = User::pluck('id'); // ou User::where('role', 'superviseur')->pluck('id')
        $notify->users()->attach($userIds);

        return $notify;
    }

    public function getNotifications()
    {
        $user = Auth::user();
        $notif = $user->notifications()
            ->wherePivot('is_read', false)
            ->latest()
            ->first();

        if ($notif) {
            // Marquer uniquement pour cet utilisateur
            $user->notifications()->updateExistingPivot($notif->id, ['is_read' => true]);

            return response()->json([
                'new' => true,
                'data' => $notif
            ]);
        }

        return response()->json(['new' => false]);
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

    public function getElements(){
        $elements = SupervisionControlElement::orderBy("id", "ASC")->get();
        return response()->json([
            "elements"=>$elements
        ]);
    }
}
