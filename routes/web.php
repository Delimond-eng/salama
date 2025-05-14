<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppManagerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TalkieWalkieController;
use App\Models\Site;
use App\Models\Announce;

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
        $sites = Site::where('status', 'actif')->where('agency_id', $agencyId)->get();
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
            ->get();
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
    | SUPPRESSION GÉNÉRIQUE
    |--------------------------------------------------------------------------
    */
    Route::post('/delete', [AppManagerController::class, 'triggerDelete'])->name('delete');

    /*
    |--------------------------------------------------------------------------
    | TALKIE-WALKIE
    |--------------------------------------------------------------------------
    */
    Route::post('/send.talk', [TalkieWalkieController::class, 'sendTalkAudio'])->name('send.talk');
});
