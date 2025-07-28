<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppManagerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\TalkieWalkieController;
use App\Models\Site;
use Carbon\Carbon;
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
Route::middleware(["geo.restricted.api","check.api.key","cors"])->group(function(){
    //ALLOW TO CREATE AGENCY
    Route::post("/agency.create", [AdminController::class, "createAgencie"])->name("agency.create");

    //ALLOW TO COMPLETE AREA WITH GPS DATA LATLNG
    Route::post("area.complete", [AdminController::class, "completeArea"])->name("area.complete");

    //Insert site token
    Route::post("site.token", [AdminController::class, "completeToken"])->name("site.token");

    // AGENT ENROLL PHOTO
    Route::post("agent.enroll", [AdminController::class, "enrollAgent"])->name("area.complete");

    //ALLOW TO MAKE PATROL SCAN RECORD
    Route::post("patrol.scan", [AppManagerController::class, "startPatrol"])->name("patrol.scan");

    //ALLOW TO CLOSE PATROL SCAN
    Route::post("patrol.close", [AppManagerController::class, "closePatrolTag"])->name("patrol.close");

    //ALLOW TO CREATE ROND 011
    Route::post("ronde.scan", [AppManagerController::class, "confirmRonde011"])->name("ronde.scan");


    //ALLOW TO VIEW PENDING PATROLS
    Route::get("/patrols.pending", [AppManagerController::class, "viewPendingPatrols"])->name("patrols.pending");
    
    //VIEW PENDING PATROLS BY SITE
    Route::get("/site.patrol.pending", [AppManagerController::class, "getPendingPatrol"])->name("site.patrol.pending");

    //ALLOW TO LOAD ALL ANNOUNCES FROM MOBILE APP
    Route::get("/announces.load", [AppManagerController::class, "loadAnnouncesFromMobile"])->name("announces.load");

    //ALLOW TO AUTHENTICATE AGENT
    Route::post("/agent.login", [AppManagerController::class, "loginAgent"])->name("agent.login");

    //ALLOW TO CREATE SIGNALEMENT
    Route::post("/signalement.create", [AppManagerController::class, "createSignalement"])->name("signalement.create");

    //ALLOW TO CREATE REQUEST
    Route::post("/request.create", [AppManagerController::class, "createRequest"])->name("request.create");

    //ALLOW TO CREATE AGENT PHONE LOG
    Route::post("/log.create", [LogController::class, 'createPhoneLog'])->name('log.create');

    //ALLOW TO GET ALL SCHEDULES
    Route::get("/schedules.all", [AppManagerController::class, "viewAllSchedulesByApp"])->name("schedules.all");

    Route::post('/horaire.create', [PresenceController::class, 'createHoraire'])->name('horaire.create');
    //pour la creation de presence agent
    Route::post('/presence.create', action: [PresenceController::class, 'createPresenceAgent'])->name('presence.create');
    //Enregistre la visit d'un superviseur au site programmé
    Route::post('/supervisor.visit.create', [PresenceController::class, 'createSupervisorSiteVisit'])->name('supervisor.visit.create');
    //donnees presence
    Route::get('/presences', [PresenceController::class, 'getPresencesBySiteAndDate'])->name('presences');
    //Emettre sur un canal de talkie walkie
    Route::post('/send.talk', [TalkieWalkieController::class, 'sendTalkAudio']);

    Route::get("/patrols.reports", [AppManagerController::class, "viewPatrolReports"])->name("patrols.reports");
    //horaires
    Route::get('/horaires', [PresenceController::class, 'getAllHoraires'])->name('horaires');

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

    Route::post("/send.mail", [EmailController::class, "sendMail"])->name("send.mail");
    Route::post("/send.notication", [FCMController::class, "sendNotification"])->name("send.notification");

    /* Route::post("/parse.date", function(Request $req){
        Carbon::setLocale('fr');
        $date = Carbon::parse($req->input("date"));
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
        $body = "Vous avez une nouvelle patrouille $formattedDate";

        return response()->json([
            "message"=>$body
        ]);
    }); */

    // GET SUPERVISOR DATAS
    Route::get("/supervisor.datas", [AppManagerController::class, "getSupervisorDatas"])->name("supervisor.datas");

    //CLIENT LOGIN
    Route::post("/client.login", [ClientController::class, "loginClient"])->name("client.login");

    //CLIENT VERIFY OTP
    Route::post("/client.otp", [ClientController::class, "verifyOtp"])->name("client.opt");

    //VIEW CLIENT PENDING PATROL
    Route::get("/client.patrol.pending", [ClientController::class, "getPendingPatrol"])->name("client.patrol.pending");

    //VIEW PATROL HISTORIES
    Route::get("/client.patrol.histories", [ClientController::class, "getPatrolHistories"])->name("client.patrol.histories");

    //VIEW CLIENT AGENTS PRESENT
    Route::get("/client.agents.presence", [ClientController::class, "getAgentPresences"])->name("client.agents.presence");

    Route::get("/sup.reports", [AdminController::class, "getDashboardData"])->name("sup.reports");
    //UPDATE CLIENT TOKEN 
    Route::post("/client.token", [ClientController::class, "updateFcmToken"])->name("client.token");
});
/* Route::get('/schedules.all', [AppManagerController::class, 'viewAllSchedulesByAdmin']);
Route::get('/schedules.generate', [AppManagerController::class, 'autoCreateNightPlannings']); */
Route::get("/check.update", function(){
    return response()->json([
        'version_code' => 3,
        'apk_url' => url('/apks/app.apk'),
        'changelog' => "- Ajout des nouvelles fonctionnalités \n- Correction des bugs \n- Interface améliorée"
    ]);
});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
