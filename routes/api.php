<?php

use App\Http\Controllers\AppManagerController;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(["cors"])->group(function(){
    //ALLOW TO CREATE AGENCY
    Route::post("/agency.create", [\App\Http\Controllers\AdminController::class, "createAgencie"])->name("agency.create");

    //ALLOW TO COMPLETE AREA WITH GPS DATA LATLNG
    Route::post("area.complete", [\App\Http\Controllers\AdminController::class, "completeArea"])->name("area.complete");

    //ALLOW TO MAKE PATROL SCAN RECORD
    Route::post("patrol.scan", [AppManagerController::class, "startPatrol"])->name("patrol.scan");

    //ALLOW TO CLOSE PATROL SCAN
    Route::post("patrol.close", [AppManagerController::class, "closePatrolTag"])->name("patrol.close");

    //ALLOW TO VIEW PENDING PATROLS
    Route::get("/patrols.pending", [AppManagerController::class, "viewPendingPatrols"])->name("patrols.pending");

    //ALLOW TO LOAD ALL ANNOUNCES FROM MOBILE APP
    Route::get("/announces.load", [AppManagerController::class, "loadAnnouncesFromMobile"])->name("announces.load");

    //ALLOW TO AUTHENTICATE AGENT
    Route::post("/agent.login", [AppManagerController::class, "loginAgent"])->name("agent.login");

    //ALLOW TO CREATE SIGNALEMENT
    Route::post("/signalement.create", [AppManagerController::class, "createSignalement"])->name("signalement.create");

    //ALLOW TO CREATE REQUEST
    Route::post("/request.create", [AppManagerController::class, "createRequest"])->name("request.create");

    //ALLOW TO GET ALL SCHEDULES
    Route::get("/schedules.all", [AppManagerController::class, "viewAllSchedulesByApp"])->name("schedules.all");

    Route::post('/horaire.create', [\App\Http\Controllers\PresenceController::class, 'createHoraire'])->name('horaire.create');
    //pour la creation de presence agent
    Route::post('/presence.create', [\App\Http\Controllers\PresenceController::class, 'createPresenceAgent'])->name('presence.create');
    //donnees presence
    Route::get('/presences', [\App\Http\Controllers\PresenceController::class, 'getPresencesBySiteAndDate'])->name('presences');
    //Emettre sur un canal de talkie walkie
    Route::post('/send.talk', [\App\Http\Controllers\TalkieWalkieController::class, 'sendTalkAudio']);

    Route::get("/patrols.reports", [AppManagerController::class, "viewPatrolReports"])->name("patrols.reports");
    //horaires
    Route::get('/horaires', [\App\Http\Controllers\PresenceController::class, 'getAllHoraires'])->name('horaires');

    Route::get("/sites", function () {
        $agencyId = Auth::user()->agency_id ?? 1;
        $sites = Site::where("agency_id", $agencyId)
            ->with([
                "areas" => function ($query) {
                    return $query->where("status", "actif");
            }])
            ->get();
        return response()->json([
            "sites" => $sites
        ]);
    });

     Route::get("/patrols.pending", [AppManagerController::class, "viewPendingPatrols"])->name("patrols.pending");
});


/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
