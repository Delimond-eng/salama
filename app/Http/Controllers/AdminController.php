<?php

namespace App\Http\Controllers;

use App\Models\Agencie;
use App\Models\Agent;
use App\Models\Area;
use App\Models\Schedules;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminController extends Controller
{
    /**
     * CREATE AGENCY
     * @param Request $request
     * @return JsonResponse
     */
    public function createAgencie(Request $request)
    {
        try{
            $data = $request->validate([
                "name"=>"required|string|unique:agencies,name",
                "adresse"=>"required|string",
                "logo"=>"nullable|file",
                "phone"=>"nullable|string",
                "email"=>"email|string",
                "password"=>"required|string",
                "username"=>"required|string"
            ]);
            $response = Agencie::create($data);
            if($response){
                User::create([
                    "name"=>$data["username"],
                    "password"=>bcrypt($data["password"]),
                    "email"=>$data["email"],
                    "agency_id"=>$response->id
                ]);
            }
            return response()->json([
                "status"=>"success",
                "response"=>$response
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
     * Add or Create Site for Agency and areas
     * @param Request $request
     * @return JsonResponse
     */
    public function createAgencieSite(Request $request):JsonResponse
    {
        try{
            $data = null;
            if($request->id){
                $data = $request->validate([
                    "areas.*.libelle"=>"required|string",
                ]);
                //Crée les zones de patrouille pour un site nouvellement créé
                $areas = $data["areas"];
                foreach ($areas as $area) {
                    $area['site_id'] = $request->id;
                    $latestArea = Area::updateOrCreate(
                        [
                            "site_id"=>$area["site_id"],
                            "libelle"=>$area["libelle"]
                        ],
                        $area
                    );
                    $json = $latestArea->toJson();
                    $qrCode = $this->generateQRCode($json);
                    $latestArea->qrcode = $qrCode;
                    $latestArea->save();
                }
                return response()->json([
                    "status"=>"success",
                    "result"=>"Nouveaux sites ajoutés avec succès !"
                ]);
            }
            else{
                $data = $request->validate([
                    "name"=>"required|string",
                    "code"=>"required|string|unique:sites,code",
                    "latlng"=>"nullable|string",
                    "adresse"=>"required|string",
                    "phone"=>"nullable|string",
                    "areas.*.libelle"=>"required|string",
                ]);
                $data["agency_id"] = Auth::user()->agency_id;
                $response = Site::updateOrCreate(
                    [
                        "code"=>$data["code"],
                        "agency_id"=>$data["agency_id"],
                    ],
                    $data
                );
                if($response){
                    //Crée les zones de patrouille pour un site nouvellement créé
                    $areas = $data["areas"];
                    foreach ($areas as $area) {
                        $area['site_id'] = $response->id;
                        $latestArea = Area::updateOrCreate(
                            [
                                "site_id"=>$area["site_id"],
                                "libelle"=>$area["libelle"]
                            ],
                            $area
                        );
                        $json = $latestArea->toJson();
                        $qrCode = $this->generateQRCode($json);
                        $latestArea->qrcode = $qrCode;
                        $latestArea->save();
                    }
                }
                return response()->json([
                    "status"=>"success",
                    "result"=>$response
                ]);
            }
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
     * complete existing area with GPS DATA
     * @return JsonResponse
    */
    public function completeArea(Request $request) : JsonResponse{
        try {
            $data = $request->validate([
                "area_id"=>"required|int|exists:areas,id",
                "latlng"=>"required|string"
            ]);

            $area = Area::where("status", "actif")->where("id", $data["area_id"])->first();
            if($area){
                $area->latlng = $data["latlng"];
                $area->save();
                return response()->json([
                    "status" => "success",
                    "result" => $area
                ]);
            }
            else{
                return response()->json(['errors' => "Zone scannée non valide ." ]);
            }
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
     * Generate qrcode data image
    */
    private function generateQRCode($data):string
    {
        $qrCode = QrCode::size(50)->generate($data);
        return 'data:image/png;base64,' . base64_encode($qrCode);
    }

    /**
     * View all agents by agency
     * @return JsonResponse
    */
    public function viewAllSites(){
        $user = Auth::user();
        $datas = Site::with('agencie')
            ->where('status', 'actif')
            ->where('agency_id', $user->agency_id)
            ->orderByDesc('id')
            ->get();
        return response()->json([
            "status"=>"success",
            "sites"=>$datas
        ]);
    }



    /**
     * CREATE OR ASSIGN AGENT
     * @param Request $request
     * @return JsonResponse
    */
    public function createAgent(Request $request){
        try{
            $data = null;
            if($request->id){
                $data = $request->validate([
                    "site_id"=>"required|int|exists:sites,id"
                ]);
                $foundAgent = Agent::find($request->id);
                $foundAgent->site_id = $data["site_id"];
                $foundAgent->save();
                return response()->json([
                    "status"=>"success",
                    "result"=>$foundAgent
                ]);
            }else{
                $data = $request->validate([
                    "matricule"=>"required|string|unique:agents,matricule",
                    "fullname"=>"required|string",
                    "password"=>"required|string",
                    "site_id"=>"nullable|int|exists:sites,id",
                    "role"=>"nullable|string"
                ]);
                $data["agency_id"] = Auth::user()->agency_id;
                $response = Agent::updateOrCreate(
                    [
                        "agency_id"=>$data["agency_id"],
                        "matricule"=>$data["matricule"],
                    ],
                    $data
                );
                return response()->json([
                    "status"=>"success",
                    "result"=>$response
                ]);
            }
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
     * View all agents by agency
     * @return JsonResponse
    */
    public function viewAllAgents(){
        $user = Auth::user();
        $datas = Agent::with('agencie')->with('site')
            ->where('status', 'actif')
            ->where('agency_id', $user->agency_id)
            ->orderByDesc('id')
            ->get();
        return response()->json([
            "status"=>"success",
            "agents"=>$datas
        ]);
    }


    /**
     * CREATE SCANNING SCHEDULES
     * @param Request $request
     * @return JsonResponse
    */
    public function createScanningSchedule(Request $request){
        try{
            $data = $request->validate([
                "libelle"=>"required|string",
                "start_time"=>"required|string",
                "end_time"=>"nullable|string",
                "site_id"=>"required|int|exists:sites,id"
            ]);
            $response = Schedules::updateOrCreate(
                [
                    "libelle"=>$data["libelle"],
                    "site_id"=>$data["site_id"],
                ],
                $data
            );
            return response()->json([
                "status"=>"success",
                "response"=>$response
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
     * VIEW ALL SCHEDULES
     * @return JsonResponse
    */
    public function viewAllScanSchedules(){
        $user = Auth::user();
        $datas = Schedules::with('site')
                    ->where("status", "actif")
                    ->where("agency_id", $user->agency_id)
                    ->orderByDesc('id')
                    ->get();
        return response()->json([
            "status" => "success",
            "schedules"=> $datas
        ]);
    }


}
