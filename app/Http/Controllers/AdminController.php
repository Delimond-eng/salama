<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Agencie;
use App\Models\Agent;
use App\Models\AgentHistory;
use App\Models\AgentGroupAssignment;
use App\Models\Area;
use App\Models\Conge;
use App\Models\Menu;
use App\Models\Patrol;
use App\Models\Schedules;
use App\Models\Site;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
   public function createAgencieSite(Request $request): JsonResponse
    {
        try {
            // Si un ID est présent, on met à jour le site existant
            if ($request->id) {
                $data = $request->validate([
                    "name" => "nullable|string",
                    "code" => "nullable|string|unique:sites,code," . $request->id,
                    "secteur_id" => "nullable|int|exists:secteurs,id",
                    "latlng" => "nullable|string",
                    "adresse" => "nullable|string",
                    "phone" => "nullable|string",
                    "client_email" => "nullable|string",
                    "areas.*.libelle" => "nullable|string",
                    "presence" => "nullable|int",
                    "emails" => "nullable|string",
                ]);

                $site = Site::findOrFail($request->id);

                // Mise à jour uniquement des champs non nuls, hors 'areas'
                foreach ($data as $key => $value) {
                    if ($value !== null && $key !== 'areas') {
                        $site->$key = $value;
                    }
                }
                $site->save();

                // Mise à jour ou ajout des zones si présentes
                if (isset($data['areas'])) {
                    foreach ($data['areas'] as $area) {
                        if(!empty($area['libelle'])){
                            $area['site_id'] = $site->id;
                            $latestArea = Area::updateOrCreate(
                                [
                                    "site_id" => $area["site_id"],
                                    "libelle" => $area["libelle"]
                                ],
                                $area
                            );
                            $json = $latestArea->toJson();
                            $qrCode = $this->generateQRCode($json);
                            $latestArea->qrcode = $qrCode;
                            $latestArea->save();
                        }
                    }
                }

                return response()->json([
                    "status" => "success",
                    "result" => "Site mis à jour avec succès !"
                ]);
            }

            // Sinon, on crée un nouveau site
            else {
                $data = $request->validate([
                    "name" => "required|string",
                    "code" => "required|string|unique:sites,code",
                    "secteur_id" => "required|int|exists:secteurs,id",
                    "latlng" => "nullable|string",
                    "adresse" => "required|string",
                    "phone" => "nullable|string",
                    "client_email" => "nullable|string",
                    "areas.*.libelle" => "required|string",
                    "presence" => "nullable|int",
                    "emails" => "nullable|string",
                ]);

                $data["agency_id"] = Auth::user()->agency_id;

                $site = Site::create($data);

                foreach ($data["areas"] as $area) {
                    $area['site_id'] = $site->id;
                    $latestArea = Area::updateOrCreate(
                        [
                            "site_id" => $area["site_id"],
                            "libelle" => $area["libelle"]
                        ],
                        $area
                    );
                    $json = $latestArea->toJson();
                    $qrCode = $this->generateQRCode($json);
                    $latestArea->qrcode = $qrCode;
                    $latestArea->save();
                }

                return response()->json([
                    "status" => "success",
                    "result" => $site
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
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
                "latlng"=>"required|string",
                "libelle"=>"nullable|string"
            ]);

            $area = Area::where("status", "actif")->where("id", $data["area_id"])->first();
            if($area){
                $area->latlng = $data["latlng"];
                $area->libelle = $data["libelle"] ?? $area->libelle;
                $area->save();
                $site = Site::find($area->site_id);
                $site->latlng = $data["latlng"];
                $site->save();
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
     *Generate Site Qrcodes
     * @return mixed
    */
    public function generateSiteQrcodes(Request $request)
    {
        try {
            $sites = Site::select("id","name","code","latlng")->orderBy("name")->get();
            $data = [];

            foreach ($sites as $site) {
                $json = $site->toJson();
                $qrCode = $this->generateQRCode($json);
                $data[] = [
                    'name' => $site->name,
                    'qrcode' => $qrCode
                ];
            }

            $pdf = PDF::loadView('pdf.qrcodes', ['areas' => $data])
                    ->setPaper('A4', 'portrait');
            return $pdf->download('qrcodes-sites.pdf');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    /**
     * complete notification token
     * @return JsonResponse
    */
    public function completeToken(Request $request) : JsonResponse{
        try {
            $data = $request->validate([
                "site_id"=>"required|int|exists:sites,id",
                "fcm_token"=>"required|string"
            ]);

            $site = Site::find($data["site_id"]);
            if($site){
                $site->fcm_token = $data["fcm_token"];
                $site->save();
                return response()->json([
                    "status" => "success",
                    "result" => $site
                ]);
            }
            else{
                return response()->json(['errors' => "Echec." ]);
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
     * Enroll agent with photo
     * @return JsonResponse
    */
    public function enrollAgent(Request $request) : JsonResponse{
        try {
            $data = $request->validate([
                "matricule"=>"required|string|exists:agents,matricule",
            ]);
            $agent = Agent::where("matricule", $data["matricule"])->first();
            if ($request->hasFile('photo') && isset($agent)) {
                $file = $request->file('photo');
                $filename = uniqid('agent_') . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/agents');
                $file->move($destination, $filename);
                // Générer un lien complet sans utiliser storage
                $data['photo'] = url('uploads/agents/' . $filename);
            }

            $agent->update([
                "photo"=>$data["photo"]
            ]);

            return response()->json([
                "status"=>"success",
                "result"=>$agent
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
        (new Carbon())->setTimezone("Africa/Kinshasa");
        try{
            $data = $request->validate([
                "matricule"=>"required|string",
                "fullname"=>"required|string",
                "password"=>"required|string",
                "site_id"=>"nullable|int|exists:sites,id",
                "role"=>"nullable|string",
                "status"=>"nullable|string",
                "groupe_id"=>"nullable|int|exists:agent_groups,id",
            ]);
            if ($request->hasFile('photo') && !isset($data["patrol_id"])) {
                $file = $request->file('photo');
                $filename = uniqid('agent_') . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/agents');
                $file->move($destination, $filename);
                // Générer un lien complet sans utiliser storage
                $data['photo'] = url('uploads/agents/' . $filename);
            }
            $data["agency_id"] = Auth::user()->agency_id;

            $agent = null;

            if ($request->filled("id")) {
                $agent = Agent::find($request->id);
            }
            $response = Agent::updateOrCreate(
                [
                    "matricule" => $data["matricule"],
                    "id" => $request->id ?? null,
                ],
                $data
            );

            $response->status = $data["status"] ?? "permenant";
            $response->save();

            if ($response) {
                AgentGroupAssignment::updateOrCreate(
                    ["agent_id"=>$response->id],
                    [
                        "agent_id" => $response->id,
                        "agent_group_id" => $response->groupe_id, 
                        "start_date" => Carbon::today()->setTimezone("Africa/Kinshasa"),
                    ]
                );
                if($agent && $agent->site_id){
                    $this->createAgentHistory($response, $agent->site_id);
                }
            }
            return response()->json([
                "status" => "success",
                "result" => $response
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
     * Allow to import agents List Excel
     * @param Request $request
     * @return JsonResponse
    */
    public function importAgentsListToExcel(Request $request)
    {
        try {
            $data = $request->validate([
                'file' => 'required|file|mimes:xlsx,xls',
            ]);

            DB::beginTransaction(); // Sécurise les insertions

            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $total = 0;
            $ajoutes = 0;
            $ignores = 0;
            $newSites = 0;

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Ignorer l'en-tête

                $matricule = preg_replace('/\s+/', '', $row[0]);
                $noms = trim($row[1]);
                $siteName = trim((string)$row[2]);
                $siteAdresse = trim((string)$row[3]);

                $total++;

                // Chercher le site existant
                $site = Site::where('name', 'LIKE', "%{$siteName}%")->first();

                // S'il n'existe pas, le créer avec nouveau code
                if (!$site && $siteName !== '') {
                    $lastSite = Site::latest('id')->first();
                    $lastCode = $lastSite->code;
                    $code = $this->incrementCode($lastCode);
                    $site = Site::updateOrCreate(
                        ["code"=>$code],
                        [
                        'code'    => $code,
                        'name'    => $siteName,
                        'adresse' => $siteAdresse,
                        'agency_id'=>1
                    ]);
                    $newSites++;
                }

                // Vérifier si l’agent avec même matricule et site existe déjà
                $agentExiste = Agent::where('matricule', $matricule)
                                    ->exists();
                if ($agentExiste) {
                    $ignores++;
                    continue;
                }

                Agent::updateOrCreate(
                    ['matricule' => $matricule],
                    [
                        'fullname' => $noms,
                        'site_id'  => $site ? $site->id : null,
                        'password' => str_pad(rand(0, 999999), rand(4, 6), '0', STR_PAD_LEFT),
                        'agency_id'=> 1
                    ]
                );
                $ajoutes++;
            }

            DB::commit(); // Tout s’est bien passé

            return response()->json([
                "status" => "success",
                "message" => "Import terminé",
                "summary" => [
                    "total" => $total,
                    "ajoutes" => $ajoutes,
                    "ignores" => $ignores,
                    "nouveaux_sites" => $newSites
                ]
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'errors' => $e->validator->errors()->all()]);
        }
        catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => $e->getMessage()]);
        }
    }


    private function incrementCode(string $code): string
    {
        if (preg_match('/^([A-Za-z]*)(\d+)$/', $code, $matches)) {
            $prefix = $matches[1];
            $number = $matches[2];
            $length = strlen($number);
            $newNumber = str_pad(((int)$number + 1), $length, '0', STR_PAD_LEFT);
            return $prefix . $newNumber;
        }
        throw new \InvalidArgumentException("Format de code invalide : $code");
    }


    /**
     * GET ALL AGENTS LIST
     * @param Request $request
     * @return JsonResponse
    */
    public function fetchAgents(Request $request)
    {
        $search = trim($request->query('search'));
        $statusFilter = $request->query('status');
        $siteFilter = $request->query('site');
        $agencyId = Auth::user()->agency_id;

        $agents = Agent::where('agency_id', $agencyId)
            ->with(['site', 'groupe', 'stories.site', 'stories.from'])
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when($siteFilter, function ($query, $siteFilter) {
                $query->where('site_id', $siteFilter);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('matricule', 'LIKE', "%$search%")
                    ->orWhere('fullname', 'LIKE', "%$search%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'agents' => $agents
        ]);
    }


    /**
     * Crée l'historique de mouvement des agents
     * @return AgentHistory
    */
    protected function createAgentHistory(Agent $agent, $siteId = null):void
    {
        // Vérifie que les deux IDs sont définis et différents
        if (!is_null($agent->site_id) && !is_null($siteId) && $agent->site_id != $siteId) {
             AgentHistory::create([
                "agent_id" => $agent->id,
                "site_id" => $agent->site_id, // ancien site
                "site_provenance_id" => $siteId, // nouveau site demandé
                "status" => $agent->status,
                "date" => Carbon::today()->setTimezone("Africa/Kinshasa"),
            ]);
        }
    }


    /**
     * View all agents histories
     * @return JsonResponse
    */
    public function viewAgentHistories(Request $request){
        $date = $request->query("date");
        $siteId = $request->query("site");
        $search = $request->query("search");
        $histories = AgentHistory::with('agent')->with("site")
        ->with("from")
        ->when($date, function ($query, $date) {
                $query->whereDate("date", $date);
            })
        ->when($siteId, function ($query, $siteId) {
                $query->where("site_id", $siteId)
                ->orWhere("site_provenance_id", $siteId);
            })
        ->when($search, function ($query) use ($search) {
                $query->whereHas("agent", function ($agentQuery) use ($search) {
                    $agentQuery->where("matricule", "LIKE", "%$search%")
                    ->orWhere("fullname", "LIKE", "%$search%");
                });
            })->orderByDesc('created_at')-> paginate(10);

        return response()->json([
            "status"=>"success",
            "histories"=>$histories
        ]);
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

     /**
     * Delete from specify database
     * @return JsonResponse
    */
    public function triggerDelete(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'table'=>'required|string',
                'id'=>'required|int'
            ]);
            $result = DB::table($data['table'])
                ->where("id", $data['id'])
                ->delete();
            return response()->json([
                "status"=>"success",
                "result"=>$result
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
     * CREATE NEW USER
     * @param Request $request
     * @return JsonResponse
    */
    public function createUser(Request $request){
        try{
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|string',
                'password' => 'required|string|min:6',
                'permissions' => 'nullable|array',
                'permissions.*.menu_id' => 'nullable|exists:menus,id',
                'permissions.*.actions' => 'nullable|array',
            ]);

            DB::beginTransaction();

            // Création de l'utilisateur
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'agency_id'=> 1,
                'password' => bcrypt($data['password'])
            ]);

            // Attribution des permissions
            if ($user->role === 'admin') {
                // Admin : toutes les permissions
                $menus = Menu::all();
                $actions = Action::all();
                foreach ($menus as $menu) {
                    foreach ($actions as $action) {
                        $user->permissions()->create([
                            'menu_id' => $menu->id,
                            'action_id' => $action->id,
                        ]);
                    }
                }
            } else {
                // Permissions personnalisées
                if (!empty($data['permissions'])) {
                    foreach ($data['permissions'] as $perm) {
                        if (!empty($perm['actions'])) {
                            foreach ($perm['actions'] as $action) {
                                $user->permissions()->create([
                                    'menu_id' => $perm['menu_id'],
                                    'action_id' => $action["id"],
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                "status" => "success",
                "result" => $user
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
     * GET DASHBOARD DATAS
     * @param Request $request
     * @return JsonResponse
    */

    public function getDashboardData(Request $request)
    {
        $targetDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))->startOfDay()
            : Carbon::today()->setTimezone("Africa/Kinshasa")->startOfDay();

        $yesterday = $targetDate->copy()->subDay();
        $site = $request->query("site");

        $presenceBySites = Site::with([
            'presences' => function ($query) use ($targetDate, $yesterday) {
                $query->whereIn('date_reference', [
                    $targetDate->toDateString(), 
                    $yesterday->toDateString()
                ]);
            },
            'presences.agent.groupe.horaire',
            'agents',
            'secteur'
        ])->when($site, function ($query, $site) {
            $query->where("id", $site );
        })
        ->paginate(10);



        $totalPresences = 0;
        $totalAgentsAttendus = 0;

        foreach ($presenceBySites->items() as $site) {
            $agentsAttendus = $site->presence ?? 0; // Vérifie bien que $site->presence correspond au nombre attendu
            $totalAgentsAttendus += $agentsAttendus;

            // Filtrage intelligent des présences
            $filteredPresences = $site->presences->filter(function ($presence) use ($targetDate) {
                $horaire = optional($presence->agent->groupe)->horaire;
                if (!$horaire) return false;

                try {
                    $presenceDate = Carbon::parse($presence->date_reference)->startOfDay();
                    $heureDebut = Carbon::parse($horaire->started_at);
                    $heureFin = Carbon::parse($horaire->ended_at);
                } catch (\Exception $e) {
                    Log::error("Erreur parsing horaire via groupe de l'agent : " . $e->getMessage());
                    return false;
                }

                $isHoraire24h = $heureDebut->equalTo($heureFin);
                $shiftDeNuit  = $heureFin->lessThan($heureDebut);

                if ($isHoraire24h || $shiftDeNuit) {
                    // Pour les horaires 24h ou nuit, accepter aussi la veille (dateReference)
                    if (!$presence->ended_at) {
                        // Présence non terminée : doit être la veille
                        return $presenceDate->equalTo($targetDate->copy()->subDay());
                    } else {
                        // Présence terminée : accepter la veille ou le jour même
                        return in_array($presenceDate->toDateString(), [
                            $targetDate->toDateString(),
                            $targetDate->copy()->subDay()->toDateString()
                        ]);
                    }
                }

                // Horaires classiques : présence le jour même uniquement
                return $presenceDate->equalTo($targetDate);
            })->values();

            $site->filteredPresences = $filteredPresences;
            $site->presence_effective = $filteredPresences->count();
            $totalPresences += $site->presence_effective;

            $site->presence_rate = $agentsAttendus > 0
                ? round(($site->presence_effective / $agentsAttendus) * 100, 2)
                : 0;
        }

        $siteCount = Site::count();
        $pendingPatrolCount = Patrol::where("status", "pending")->count();
        $holidayAgentsCount = Conge::whereDate("date_fin", "<=", Carbon::today()->setTimezone("Africa/Kinshasa"))->count();

        return response()->json([
            'status' => 'success',
            'date' => $targetDate->toDateString(),
            'dash_presences' => $presenceBySites,
            'count' => [
                'sites' => $siteCount,
                'presences' => $totalPresences,
                'agents_attendus' => $totalAgentsAttendus,
                'holidays' => $holidayAgentsCount,
                'patrols' => $pendingPatrolCount,
            ]
        ]);
    }



    public function exportPresenceReport(Request $request)
    {
        $targetDate = $request->input('date') 
            ? Carbon::parse($request->input('date'))->startOfDay()
            : Carbon::today()->setTimezone("Africa/Kinshasa")->startOfDay();

        $yesterday = $targetDate->copy()->subDay();

        $sites = Site::with([
            'presences' => function ($query) use ($targetDate, $yesterday) {
                $query->whereIn('date_reference', [
                    $targetDate->toDateString(),
                    $yesterday->toDateString()
                ]);
            },
            'presences.agent.groupe.horaire',
            'agents',
            'secteur'
        ])->get();

        $totalPresences = 0;
        $totalAgents = 0;

        foreach ($sites as $site) {
            $presenceAttendue = $site->presence ?? 0;
            $totalAgents += $presenceAttendue;

            $filteredPresences = $site->presences->filter(function ($presence) use ($targetDate) {
                $horaire = optional($presence->agent->groupe)->horaire;
                if (!$horaire) return false;

                try {
                    $presenceDate = Carbon::parse($presence->date_reference)->startOfDay();
                    $heureDebut = Carbon::parse($horaire->started_at);
                    $heureFin   = Carbon::parse($horaire->ended_at);
                } catch (\Exception $e) {
                    Log::error("⛔ Erreur parsing horaire via groupe de l'agent : " . $e->getMessage());
                    return false;
                }

                $isHoraire24h = $heureDebut->equalTo($heureFin);
                $shiftDeNuit  = $heureFin->lessThan($heureDebut);

                if ($isHoraire24h || $shiftDeNuit) {
                    // Présences liées à la veille pour horaires 24h/nuit
                    return $presenceDate->equalTo($targetDate->copy()->subDay());
                }

                // Shifts de jour
                return $presenceDate->equalTo($targetDate);
            })->values();

            $site->presences = $filteredPresences;
            $site->presences_count = $filteredPresences->count();
            $site->presence_expected = $presenceAttendue;
            $site->presence_rate = $presenceAttendue > 0 
                ? round(($filteredPresences->count() / $presenceAttendue) * 100, 1)
                : 0;

            $totalPresences += $site->presences_count;
        }

        $pdf = Pdf::loadView('pdf.reports.presence_simple_report', [
            'sites' => $sites,
            'totalPresences' => $totalPresences,
            'totalAgents' => $totalAgents,
            'date' => $targetDate->toDateString(),
        ]);

        return $pdf->download("rapport-presence-{$targetDate->format('Y-m-d')}.pdf");
    }

}
