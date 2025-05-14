<?php

namespace App\Http\Controllers;

use App\Models\PresenceHoraire;
use Illuminate\Http\Request;

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


    public function getAllHoraires(){
        $horaires = PresenceHoraire::with('agents')->get();
        return response()->json(['horaires' => $horaires ]);
    }

}
