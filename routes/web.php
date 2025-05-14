<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppManagerController;
use App\Http\Controllers\PresenceController;
use App\Models\Agent;
use App\Models\Announce;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::middleware(["auth"])->group(function(){
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name("dashboard");

    Route::get('/agent.create', function () {
        $agencyId = Auth::user()->agency_id;
        $sites = Site::where("agency_id", $agencyId)->get();
        return view('add_agent',[
            "sites"=>$sites
        ]);
    })->name('agent.create');

    Route::get('/agents.list', function () {
        $agencyId = Auth::user()->agency_id;
        $sites = Site::where("status", "actif")->where("agency_id", $agencyId)->get();
        return view('agent_list', [
            "sites"=>$sites
        ]);
    })->name('agents.list');

    Route::get('/sites.list', function () {
        return view('site_list',);
    })->name('sites.list');

    Route::get("/sites", function () {
        $agencyId = Auth::user()->agency_id;
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


    Route::get("/agents", [ApiController::class, "fetchAgents"])->name("agents");

    Route::view('/site.create', 'add_site_area')->name('site.create');
    Route::view("/reports.patrols", "report_patrols")->name("reports.patrols");
    Route::view("/reports.tasks", "report_tasks")->name("reports.tasks");
    Route::view("/announces", "announces")->name("announces");
    Route::view("/requests", "requests")->name("requests");
    Route::view("/signalements", "signalements")->name("signalements");
    Route::view("/schedules", "schedules")->name("schedules");
    Route::view("/tasks", "tasks")->name("tasks");
    Route::view("/visit.creating", "visit_create")->name("visit.creating");
    Route::view("/presence.horaires", "presence_horaire")->name("presence.horaires");
    Route::view("/reports.presences", "report_presences")->name("reports.presences");

    Route::view("/log.phones", "log_phone")->name("log.phones");
    Route::view("/log.activities", "log_activity")->name("log.activities");
    Route::view("/log.panics", "log_panic")->name("log.panics");


    //VIEW ALL ANNOUNCES
    Route::get("/announces.all", function (){
        $agencyId = Auth::user()->agency_id;
        $announces = Announce::with("site")
            ->where("status", "actif")
            ->where("agency_id", $agencyId)
            ->get();
        return response()->json([
            "status"=>"success",
            "announces"=>$announces
        ]);
    });

    //ALLOW TO CREATE SITE
    Route::post("site.create", [AdminController::class, "createAgencieSite"])->name("site.create");

    //ALLOW TO CREATE AGENT
    Route::post("agent.create", [AdminController::class, "createAgent"])->name("agent.create");

    //ALLOW TO CREATE ANNOUNCE
    Route::post("announce.create", [AppManagerController::class, "createAnnounce"])->name("announce.create");

    //ALLOW TO CREATE SCHEDULES
    Route::post("schedules.create", [AppManagerController::class, "createPlanning"])->name("schedules.create");

    //ALL TO DELETE ANYTHING
    Route::post("delete", [AppManagerController::class, "triggerDelete"])->name("delete");

    //LOAD & DOWNLOAD AREA PDF CONTENT QRCODE FOR SCANNING
    Route::get("/loadpdf/{siteId}", [AppManagerController::class, "generatePdfWithQRCodes"])->name("loadpdf");

    Route::get("/pdf.patrols.reports", [AppManagerController::class, "generatePatrolPdfReport"])->name("pdf.patrols.reports");

    //VIEW ALL PENDING PATROLS
    Route::get("/patrols.pending", [AppManagerController::class, "viewPendingPatrols"])->name("patrols.pending");

    //VIEW PATROLS REPORTS
    Route::get("/patrols.reports", [AppManagerController::class, "viewPatrolReports"])->name("patrols.reports");

    //VIEW REQUESTS
    Route::get("/requests.all", [AppManagerController::class, "viewAllRequests"])->name("requests.all");

    //VIEW SIGNALEMENTS
    Route::get("/signalements.all", [AppManagerController::class, "viewAllSignalements"])->name("signalements.all");

    //VIEW SCHEDULES
    Route::get("/schedules.all", [AppManagerController::class, "viewAllSchedulesByAdmin"])->name("schedules.all");

    Route::get("/horaires",[PresenceController::class, "getAllHoraires"]);
    Route::post("/horaire.create",[PresenceController::class, "createHoraire"])->name("horaire.create");


    //Emettre sur un canal de talkie walkie
    Route::post('/send.talk', [\App\Http\Controllers\TalkieWalkieController::class, 'sendTalkAudio'])->name('send.talk');

});
