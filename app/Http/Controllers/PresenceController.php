<?php

namespace App\Http\Controllers;

use App\Models\PresenceHoraire;
use App\Models\PresenceAgents;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Site;
use App\Models\Agent;

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
                    "libelle"=>$data["libelle"],
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

            $agent = Agent::where('matricule', $data['matricule'])->firstOrFail();
            $now = Carbon::now();

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
                    $d = app(AppManagerController::class)->calculateDistance($lat1, $lng1, $lat2, $lng2);
                    if ($d < $minDistance) {
                        $minDistance = $d;
                        $siteProche = $s;
                    }
                }
                // Si on trouve un site proche à moins de 200m
                if ($siteProche && $minDistance <= 200) {
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
                    ($distance <= 100 ? "sorti du site" : "pas dans le site à la sortie") :
                    ($distance <= 100 ? "arrivé dans le site" : "pas arrivé dans le site");
                $commentaire_distance = "$commentaire_proximite - " . round($distance) . " mètres du site";
            }

            $horaire = $agent->horaire_id ? PresenceHoraire::find($agent->horaire_id) : null;

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
                    'horaire_id' => $agent->horaire_id,
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


    public function getAllHoraires(){
        $horaires = PresenceHoraire::with('agents')->get();
        return response()->json(['horaires' => $horaires ]);
    }

}
