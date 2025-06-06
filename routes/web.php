<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppManagerController;
use App\Http\Controllers\HomeController;
use App\Models\Agent;
use App\Models\Announce;
use App\Models\Site;

 Route::post('/horaire.create', [\App\Http\Controllers\PresenceController::class, 'createHoraire'])->name('horaire.create');

Auth::routes();

Route::middleware(['auth'])->group(function () {

    // Tableau de bord
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | AGENTS
    |--------------------------------------------------------------------------
    */
    Route::get('/agent.create', function () {
        $agencyId = Auth::user()->agency_id;
        $sites = Site::where('agency_id', $agencyId)->get();
        return view('add_agent', ['sites' => $sites]);
    })->name('agent.create');

    Route::post('agent.create', [AdminController::class, 'createAgent'])->name('agent.create');

    Route::get('/agents.list', function () {
        $agencyId = Auth::user()->agency_id;
        $sites = Site::all();
        return view('agent_list', ['sites' => $sites]);
    })->name('agents.list');

    Route::get('/agents', [ApiController::class, 'fetchAgents'])->name('agents');

    /*
    |--------------------------------------------------------------------------
    | SITES & ZONES
    |--------------------------------------------------------------------------
    */
    Route::view('/site.create', 'add_site_area')->name('site.create.view');
    Route::post('site.create', [AdminController::class, 'createAgencieSite'])->name('site.create');

    Route::get('/sites.list', fn () => view('site_list'))->name('sites.list');

    Route::get('/sites', function () {
        $agencyId = Auth::user()->agency_id;
        $sites = Site::where('agency_id', $agencyId)
            ->with(['areas' => fn ($query) => $query->where('status', 'actif')])
            ->get();
        return response()->json(['sites' => $sites]);
    });

    /*
    |--------------------------------------------------------------------------
    | ANNONCES
    |--------------------------------------------------------------------------
    */
    Route::view('/announces', 'announces')->name('announces');
    Route::get('/announces.all', function () {
        $agencyId = Auth::user()->agency_id;
        $announces = Announce::with('site')
            ->where('status', 'actif')
            ->where('agency_id', $agencyId)
            ->orderByDesc("id")
            ->paginate(2);
        return response()->json(['status' => 'success', 'announces' => $announces]);
    });
    Route::post('announce.create', [AppManagerController::class, 'createAnnounce'])->name('announce.create');

    /*
    |--------------------------------------------------------------------------
    | HORAIRES / PLANNINGS
    |--------------------------------------------------------------------------
    */
    Route::view('/schedules', 'schedules')->name('schedules');
    Route::post('schedules.create', [AppManagerController::class, 'createPlanning'])->name('schedules.create');
    Route::get('/schedules.all', [AppManagerController::class, 'viewAllSchedulesByAdmin'])->name('schedules.all');
    Route::get('/schedules.verify', [AppManagerController::class, 'verifySchedules'])->name('schedules.verify');

    /*
    |--------------------------------------------------------------------------
    | RAPPORTS
    |--------------------------------------------------------------------------
    */
    Route::view('/reports.patrols', 'report_patrols')->name('reports.patrols');
    Route::view('/reports.tasks', 'report_tasks')->name('reports.tasks');
    Route::view('/reports.presences', 'report_presences')->name('reports.presences');

    Route::get('/pdf.patrols.reports', [AppManagerController::class, 'generatePatrolPdfReport'])->name('pdf.patrols.reports');

    Route::get('/patrols.pending', [AppManagerController::class, 'viewPendingPatrols'])->name('patrols.pending');
    Route::get('/patrols.reports', [AppManagerController::class, 'viewPatrolReports'])->name('patrols.reports');

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
    Route::view('/requests', 'requests')->name('requests');
    Route::get('/requests.all', [AppManagerController::class, 'viewAllRequests'])->name('requests.all');

    Route::view('/signalements', 'signalements')->name('signalements');
    Route::get('/signalements.all', [AppManagerController::class, 'viewAllSignalements'])->name('signalements.all');

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    */
    Route::view('/log.phones', 'log_phone')->name('log.phones');
    Route::view('/log.activities', 'log_activity')->name('log.activities');
    Route::view('/log.panics', 'log_panic')->name('log.panics');

    /*
    |--------------------------------------------------------------------------
    | PDF & QR CODES
    |--------------------------------------------------------------------------
    */
    Route::get('/loadpdf/{siteId}', [AppManagerController::class, 'generatePdfWithQRCodes'])->name('loadpdf');



     /*
    |--------------------------------------------------------------------------
    | PRESENCES HORAIRES
    |--------------------------------------------------------------------------
    */

    Route::post('/horaire.create', [\App\Http\Controllers\PresenceController::class, 'createHoraire'])->name('horaire.create');
    
    Route::post('/group.create', [\App\Http\Controllers\PresenceController::class, 'createGroup'])->name('group.create');

    Route::get('/horaires', [\App\Http\Controllers\PresenceController::class, 'getAllHoraires'])->name('horaires');
    
    Route::get('/groups', [\App\Http\Controllers\PresenceController::class, 'getAllGroups'])->name('groups');

     /*
    |--------------------------------------------------------------------------
    | PRESENCES DES AGENTS
    |--------------------------------------------------------------------------
    */
    Route::post('/presence.create', [\App\Http\Controllers\PresenceController::class, 'createPresenceAgent'])->name('presence.create');

    Route::get('/presences', [\App\Http\Controllers\PresenceController::class, 'getPresencesBySiteAndDate'])->name('presences');


    //Emettre sur un canal de talkie walkie
    Route::post('/send.talk', [\App\Http\Controllers\TalkieWalkieController::class, 'sendTalkAudio'])->name('send.talk');

    Route::post('/table.delete', [AdminController::class, 'triggerDelete'])->name('table.delete');

});
