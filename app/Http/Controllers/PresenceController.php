<?php

namespace App\Http\Controllers;

use App\Models\PresenceHoraire;
use App\Models\PresenceAgents;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
     public function createPresenceAgent(Request $request) {
        try {
            $data = $request->validate([
                "id" => "nullable|int|exists:presence_agents,id",
                "agent_id" => "required|int|exists:agents,id",
                "site_id" => "nullable|int|exists:sites,id",
                "horaire_id" => "required|int|exists:presence_horaires,id",
                "started_at" => "nullable|string",
                "ended_at" => "nullable|string",
                "status_photo_debut" => "nullable|string",
                "status_photo_fin" => "nullable|string",
                "commentaires" => "nullable|string",
                "status" => "nullable|string",
            ]);

            // Gérer upload de la photo de débutd
            if ($request->hasFile('photos_debut')) {
                $photo = $request->file('photos_debut');
                $filename = time() . '_debut_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/presence_photos'), $filename);
                $data['photos_debut'] = url('uploads/presence_photos/' . $filename);
            }

            // Gérer upload de la photo de fin
            if ($request->hasFile('photos_fin')) {
                $photo = $request->file('photos_fin');
                $filename = time() . '_fin_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/presence_photos'), $filename);
                $data['photos_fin'] = url('uploads/presence_photos/' . $filename);
            }

            // Cas de création (début)
            if (empty($data['id'])) {
                $horaire = \App\Models\PresenceHoraire::find($data['horaire_id']);

                $heure_attendue = strtotime($horaire->started_at);
                $heure_arrivee = strtotime($data['started_at']);
                $diff_minutes = ($heure_arrivee - $heure_attendue) / 60;
                $retard = $diff_minutes > 15 ? "en retard de " . round($diff_minutes) . " minutes" : "arrive à temps";

                $data['retard'] = $diff_minutes > 15 ? "oui" : "non";
                $data['commentaires'] = $retard;
                $data['status'] = "debut";

                $presence = \App\Models\PresenceAgents::create($data);

                return response()->json([
                    "status" => "success",
                    "message" => "Présence démarrée.",
                    "result" => $presence
                ]);
            }

            // Cas de mise à jour (fin)
            $presence = \App\Models\PresenceAgents::find($data['id']);
            $horaire = \App\Models\PresenceHoraire::find($data['horaire_id']);

            $start = new \DateTime($presence->started_at);
            $end = new \DateTime($data['ended_at']);

            if ($end < $start) {
                $end->modify('+1 day');
            }

            $interval = $start->diff($end);
            $heures = $interval->h + ($interval->days * 24);
            $minutes = $interval->i;
            $duree_formattee = "{$heures}h{$minutes}min";

            $expected_end_time = new \DateTime($horaire->ended_at);
            $horaire_start = new \DateTime($horaire->started_at);
            if ($expected_end_time < $horaire_start) {
                $expected_end_time->modify('+1 day');
            }

            $comment_fin = ($end < $expected_end_time) ? " | Parti tôt." : " | Parti à l’heure.";

            $presence->update([
                "ended_at" => $data['ended_at'],
                "duree" => $duree_formattee,
                "retard" => $presence->retard ?? "no",
                "photos_fin" => $data['photos_fin'] ?? $presence->photos_fin,
                "status_photo_fin" => $data['status_photo_fin'] ?? null,
                "commentaires" => $presence->commentaires . $comment_fin,
                "status" => "sortie"
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Présence clôturée.",
                "result" => $presence
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage()]);
        }
    }



    public function getPresencesBySiteAndDate(Request $request)
    {
        try {
            $request->validate([
                'site_id' => 'required|int|exists:sites,id',
                'date' => 'sometimes|date_format:Y-m-d',
            ]);

            $siteId = $request->site_id;
            $date = $request->date ?? Carbon::today()->toDateString();

            $presences = PresenceAgents::with(['agent', 'horaire'])
                ->where('site_id', $siteId)
                ->whereDate('created_at', $date)
                ->orderByRaw("
                    CASE
                        WHEN retard = 'no' THEN 0
                        WHEN retard IS NULL THEN 1
                        WHEN retard = 'yes' THEN 2
                        ELSE 3
                    END
                ")
                ->orderBy('started_at', 'asc')
                ->get();

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
