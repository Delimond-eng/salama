<?php

use App\Models\SitePlanningConfig;
use Google\Service\AnalyticsData\OrderBy;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppManagerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PresenceController;
use App\Models\Action;
use App\Models\Agent;
use App\Models\Announce;
use App\Models\Menu;
use App\Models\Secteur;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request; 

Auth::routes();

Route::middleware(['geo.restricted','auth'])->group(function () {

    // Tableau de bord
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::view('/global.view', 'tbd')->name('global.view');
    Route::get("/global.view.req", [AdminController::class, "getDashboardData"])->name("global.view.req");
    Route::get("/global.view.export", [AdminController::class, "exportPresenceReport"])->name("global.view.export");

    /*
    |--------------------------------------------------------------------------
    | AGENTS/presences
    |--------------------------------------------------------------------------
    */
    Route::get('/agent.create', function () {
        $agencyId = Auth::user()->agency_id;
        $sites = Site::where('agency_id', $agencyId)->get();
        return view('add_agent', ['sites' => $sites]);
    })->name('agent.create')->middleware('check.permission:agents,create');

    Route::post('agent.create', [AdminController::class, 'createAgent'])->name('agent.create')->middleware('check.permission:agents,create');
    Route::post('agents.import.excel', [AdminController::class, 'importAgentsListToExcel'])->name('agents.import.excel')->middleware('check.permission:agents,create');

    Route::get('/agents.list', function () {
        $agencyId = Auth::user()->agency_id;
        $sites = Site::all();
        return view('agent_list', ['sites' => $sites]);
    })->name('agents.list')->middleware('check.permission:agents,view');

    Route::view("/agents.history", "agent_history" )->name("agents.history")->middleware("check.permission:agents,view");
    Route::view("/agent.histories.single", "agent_histories_single" )->name("agent.histories.single")->middleware("check.permission:agents,view");

    Route::get('/agents', [AdminController::class, 'fetchAgents'])->name('agents')->middleware('check.permission:agents,view');
    Route::get('/agents.histories', [AdminController::class, 'viewAgentHistories'])->name('agents.histories');

    /*
    |--------------------------------------------------------------------------
    | SITES & ZONES
    |--------------------------------------------------------------------------
    */
    Route::get('/site.create', function(){
        $secteurs = Secteur::orderBy("libelle")->get();
        return view('add_site_area', ["secteurs"=>$secteurs]);
    })->name('site.create.view')->middleware('check.permission:sites,create');
    Route::post('site.create', [AdminController::class, 'createAgencieSite'])->name('site.create')->middleware('check.permission:sites,create');

    Route::get('/sites.list', function(){
        $secteurs = Secteur::orderBy("libelle")->get();
        return view('site_list', ["secteurs"=>$secteurs]);
    })->name('sites.list')->middleware('check.permission:sites,view');

    Route::get('/sites', function (Request $request) {
        $agencyId = Auth::user()->agency_id;
        $search = $request->query('search');

        $sitesQuery = Site::where('agency_id', $agencyId)
            ->with([
                'areas' => fn ($query) => $query->where('status', 'actif'),
                'secteur'
            ])
            ->select("*")
            ->selectRaw('
                EXISTS (    
                    SELECT 1 FROM areas
                    WHERE areas.site_id = sites.id AND areas.status = ?
                ) AS has_active_areas
            ', ['actif'])
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('code', 'LIKE', "%$search%")
                            ->orWhere('name', 'LIKE', "%$search%");
                });
            })
            ->orderByDesc('has_active_areas')
            ->orderBy('name');

        $sites = $request->has('page')
            ? $sitesQuery->paginate(8)
            : $sitesQuery->get();
        return response()->json(['sites' => $sites]);
    })->middleware('check.permission:sites,view');

    /*
    |--------------------------------------------------------------------------
    | ANNONCES
    |--------------------------------------------------------------------------
    */
    Route::view('/announces', 'announces')->name('announces')->middleware('check.permission:communiques,view');
    Route::get('/announces.all', function () {
        $agencyId = Auth::user()->agency_id;
        $announces = Announce::with('site')
            ->where('status', 'actif')
            ->where('agency_id', $agencyId)
            ->orderByDesc("id")
            ->paginate(2);
        return response()->json(['status' => 'success', 'announces' => $announces]);
    })->middleware('check.permission:communiques,view');
    Route::post('announce.create', [AppManagerController::class, 'createAnnounce'])->name('announce.create')->middleware('check.permission:communiques,create');

    /*
    |--------------------------------------------------------------------------
    | HORAIRES / PLANNINGS
    |--------------------------------------------------------------------------
    */
    Route::view('/schedules', 'schedules')->name('schedules')->middleware('check.permission:planning,view');

    Route::get('/schedules.report', function(){
        $agents = Agent::whereNot("role", "supervisor")->orderBy("fullname")->get();
        $supervisors = Agent::where("role", "supervisor")->orderBy("fullname")->get();
        $sites = Site::OrderBy("name")->get();
        return view('schedules_report', 
        ["agents"=>$agents, "sites"=>$sites, "supervisors"=>$supervisors]
    );

    })->name('schedules.report')->middleware('check.permission:planning,view');

    Route::get('/schedules.supervisor', function(){
        $supervisors = Agent::where("role", "supervisor")->orderBy("fullname", "asc")->get();
        return view('schedules_sup', ["supervisors"=>$supervisors]);
    })->name('schedules.supervisor')->middleware('check.permission:planning,view');
    Route::post('schedules.create', [AppManagerController::class, 'createPlanning'])->name('schedules.create')->middleware('check.permission:planning,create');
    Route::post('schedules.supervisor.create', [AppManagerController::class, 'createSupervisorPlanning'])->name('schedules.supervisor.create')->middleware('check.permission:planning,create');
    Route::get('/schedules.all', [AppManagerController::class, 'viewAllSchedulesByAdmin'])->name('schedules.all')->middleware('check.permission:planning,view');
    Route::get('/schedules.supervisor.all', [AppManagerController::class, 'viewSupervisorSchedules'])->name('schedules.supervisor.all')->middleware('check.permission:planning,view');
    Route::get('/supervisors.reports', [AppManagerController::class, 'getSupervisorSchedulesReport'])->name('supervisors.reports')->middleware('check.permission:planning,view');
    //Route::get('/schedules.verify', [AppManagerController::class, 'verifySchedules'])->name('schedules.verify');
    
    
     /*
    |--------------------------------------------------------------------------
    | RH ROUTES
    |--------------------------------------------------------------------------
    */
     Route::get("/conges.management", function(){
        $agents = Agent::all();
        return view("conges_management", ["agents"=>$agents]);
    })->name("conges.management");

    Route::get("/pointages.agents", function(){
        $agents = Agent::all();
        return view("pointages_agents", ["agents"=>$agents]);
    })->name("pointages.agents");


    Route::get("/ldd.management", function(){
        $agents = Agent::all();
        return view("ldd_management", ["agents"=>$agents]);
    })->name("ldd.management");



     /*
    |--------------------------------------------------------------------------
    | CONGES AGENTS
    |--------------------------------------------------------------------------
    */
    Route::post('/conge.create', [AppManagerController::class, 'createCongeAgent'])->name('conge.create');

    Route::get('/conges', [AppManagerController::class, 'getCongesByAgent'])->name('conges');
      /*
    |--------------------------------------------------------------------------
    | CESSATION AGENTS
    |--------------------------------------------------------------------------
    */
    Route::post('/cessation.create', [AppManagerController::class, 'createCessationAgent'])->name('cessation.create');

    Route::get('/cessations', [AppManagerController::class, 'getCessationsByAgent'])->name('cessations');
    //reporpresence
    Route::get('/presences.report', [PresenceController::class, 'getPresenceReport'])->name('presences.report');
    /*
    |--------------------------------------------------------------------------
    | RAPPORTS
    |--------------------------------------------------------------------------
    */
   
    Route::view('/reports.patrols', 'report_patrols')->name('reports.patrols')->middleware('check.permission:patrouilles,view');
    Route::view('/reports.tasks', 'report_tasks')->name('reports.tasks');
    Route::view('/reports.presences', 'report_presences')->name('reports.presences')->middleware('check.permission:presences,view');
    Route::view('/reports.presences.filter', 'presence_report_filter')->name('reports.presences.filter')->middleware('check.permission:presences,view');

    Route::get('/pdf.patrols.reports', [AppManagerController::class, 'generatePatrolPdfReport'])->name('pdf.patrols.reports')->middleware('check.permission:patrouilles,export');

    Route::get('/patrols.pending', [AppManagerController::class, 'viewPendingPatrols'])->name('patrols.pending')->middleware('check.permission:patrouilles,view');
    Route::get('/patrols.reports', [AppManagerController::class, 'viewPatrolReports'])->name('patrols.reports')->middleware('check.permission:patrouilles,view');
    Route::post('/patrol.close', [AppManagerController::class, 'closePatrolTag'])->name('patrol.close')->middleware('check.permission:patrouilles,create');

    /*
    |--------------------------------------------------------------------------
    | VISITES / PRÉSENCES
    |--------------------------------------------------------------------------
    */
    Route::view('/visit.creating', 'visit_create')->name('visit.creating');
    Route::view('/presence.horaires', 'presence_horaire')->name('presence.horaires');
    Route::view('/agent.groupe', 'agent_groupe')->name('agent.groupe');

    /*
    |--------------------------------------------------------------------------
    | REQUÊTES / SIGNALEMENTS
    |--------------------------------------------------------------------------
    */
    Route::view('/requests', 'requests')->name('requests')->middleware('check.permission:requetes,view');
    Route::get('/requests.all', [AppManagerController::class, 'viewAllRequests'])->name('requests.all')->middleware('check.permission:requetes,view');

    Route::view('/signalements', 'signalements')->name('signalements');
    Route::get('/signalements.all', [AppManagerController::class, 'viewAllSignalements'])->name('signalements.all');

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    */
    Route::view('/log.phones', 'log_phone')->name('log.phones')->middleware('check.permission:logs,view');
    Route::get("/logs.phones", [LogController::class, "getPhoneLogs"])->name("logs.phones");
    Route::view('/log.activities', 'log_activity')->name('log.activities');
    Route::view('/log.panics', 'log_panic')->name('log.panics');

    /*
    |--------------------------------------------------------------------------
    | PDF & QR CODES
    |--------------------------------------------------------------------------
    */
    Route::get('/loadpdf/{siteId}', [AppManagerController::class, 'generatePdfWithQRCodes'])->name('loadpdf')->middleware('check.permission:sites,export');
    Route::get('/sites.qrcode', [AdminController::class, 'generateSiteQrcodes'])->name('site.loadpdf')->middleware('check.permission:sites,export');
    /*
    |--------------------------------------------------------------------------
    | PRESENCES HORAIRES
    |--------------------------------------------------------------------------
    */

    Route::post('/horaire.create', [PresenceController::class, 'createHoraire'])->name('horaire.create')->middleware('check.permission:presences,create');
    
    Route::post('/group.create', [PresenceController::class, 'createGroup'])->name('group.create')->middleware('check.permission:presences,create');

    Route::get('/horaires', [PresenceController::class, 'getAllHoraires'])->name('horaires');
    
    Route::get('/groups', [PresenceController::class, 'getAllGroups'])->name('groups');

     /*
    |--------------------------------------------------------------------------
    | USER MANAGER
    |--------------------------------------------------------------------------
    */
    Route::get("/user.add", function(){
        $menus = Menu::all();
        $actions = Action::all();
        return view("add_user", [
            "actions"=>$actions,
            "menus"=>$menus
        ]);
    })->name("user.add")->middleware('check.permission:utilisateurs,create');

    Route::post("/user.create", [AdminController::class, "createUser"])->name("user.create")->middleware('check.permission:utilisateurs,create');
    Route::view("/user.list", 'user_list')->name("user.list")->middleware('check.permission:utilisateurs,view');

    Route::get("/users.all", function(){
        $users = User::with("permissions.menu")->with("permissions.action")->orderByDesc("id")->paginate(10);
        return response()->json([
            "status"=>"success",
            "users"=>$users
        ]);
    })->middleware('check.permission:utilisateurs,view');

     /*
    |--------------------------------------------------------------------------
    | PRESENCES DES AGENTS
    |--------------------------------------------------------------------------
    */

    
    Route::post('/presence.create', [PresenceController::class, 'createPresenceAgent'])->name('presence.create')->middleware('check.permission:presences,create');
    Route::get('/presences', [PresenceController::class, 'getPresencesBySiteAndDate'])->name('presences')->middleware('check.permission:presences,view');
    Route::view('/presence.plannings', 'presence_planning')->name("presence.planning")->middleware('check.permission:presences,create');
    Route::get("/weekly.plannings", [PresenceController::class, 'getWeeklyPlannings'])->name("weekly.plannings");
    Route::post('import.planning.excel', [PresenceController::class, 'importPlanning'])->name('import.planning.excel')->middleware('check.permission:presences,create');
    Route::get('/export.planning.excel', [PresenceController::class, 'exportWeeklyPlanningsDirect'])->name('export.planning.excel')->middleware('check.permission:presences,view');

     /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */
    Route::view("/secteurs", "secteurs")->name("secteurs");
    Route::view("/elements", "elements")->name("elements");
    Route::get("/secteurs.all", [\App\Http\Controllers\ConfigController::class, 'viewAllPaginateSectors'])->name("secteurs.all");
    Route::post("/secteur.create", [\App\Http\Controllers\ConfigController::class, 'createSector'])->name("secteur.create");
    Route::get("/elements.all", [\App\Http\Controllers\ConfigController::class, 'viewAllPaginateElements'])->name("elements.all");
    Route::post("/element.create", [\App\Http\Controllers\ConfigController::class, 'createElement'])->name("element.create");


    //==================== ROUND 011 ====================//
    Route::get("/ronde.reports", [AppManagerController::class, "getRonde011Report"])->name("ronde.reports");
    Route::view("/round.reports", "round011_report")->name("round.reports");


    //=================== CONFIGS =========================//
    Route::view("/config.planning", "config_planning")->name("config.planning");
    Route::get("/config.planning.get", [AppManagerController::class, "viewSitePlannings"])->name("config.planning.get");
    Route::post("/config.planning.create", [AppManagerController::class, "createOrUpdateSiteAutoPlanningConfig"])->name("config.planning.create");
    Route::post("/config.planning.activate", function(Request $request){
        $siteId = $request->input("site_id");
        $activate = $request->input("value");
        $site = Site::where("id", $siteId)->whereHas("areas")->first();
        if(!$site){
            return response()->json([
                "errors"=>"Ce site ne contient aucune zone (area) et ne peut pas être configuré."
            ]);
        }
        $result = SitePlanningConfig::where("site_id", $siteId)
        ->update(["activate" => $activate]);
        if($result){
            return response()->json([
                "status"=>"success",
                "result"=>$result
            ]);
        }
    });

    //Emettre sur un canal de talkie walkie
    Route::post('/send.talk', [\App\Http\Controllers\TalkieWalkieController::class, 'sendTalkAudio'])->name('send.talk');
    Route::post('/table.delete', [AdminController::class, 'triggerDelete'])->name('table.delete');

});
