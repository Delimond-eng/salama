<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }


    public function fetchAgents(Request $request){
        $agencyId = Auth::user()->agency_id;
        $agents = Agent::where("status", "actif")
            ->where("agency_id", $agencyId)
            ->with([
                "site" => function ($query) {
                    return $query->where("status", "actif");
            }])->with("horaire")
            ->get();
        return response()->json([
            "agents" => $agents
        ]);
    }
}
