<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\PresenceAgents;
use App\Models\AgentGroupPlanning;
use App\Models\PresenceHoraire;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    const TZ = 'Africa/Kinshasa';

    public function agentPresencePdf(Request $request, $agentId)
    {
        // Période : ?from=YYYY-MM-DD & ?to=YYYY-MM-DD ou ?year=YYYY
        $fromInput = $request->query('from');
        $toInput   = $request->query('to');
        $yearInput = $request->query('year');

        if ($yearInput) {
            $year = intval($yearInput);
            $from = Carbon::createFromDate($year, 1, 1, self::TZ)->startOfDay();
            $to   = Carbon::createFromDate($year, 12, 31, self::TZ)->endOfDay();
        } else {
            $from = $fromInput ? Carbon::parse($fromInput, self::TZ)->startOfDay() : Carbon::now(self::TZ)->startOfYear();
            $to   = $toInput ? Carbon::parse($toInput, self::TZ)->endOfDay() : Carbon::now(self::TZ)->endOfYear();
        }

        // Charger agent + relations utiles
        $agent = Agent::with(['site', 'groupe.horaire'])->findOrFail($agentId);

        // Récupérer présences sur la période
        $presences = PresenceAgents::where('agent_id', $agent->id)
            ->whereBetween('date_reference', [$from->toDateString(), $to->toDateString()])
            ->with(['horaire', 'site'])
            ->orderBy('date_reference', 'asc')
            ->get();

        // Récupérer plannings journaliers sur la période
        $plannings = AgentGroupPlanning::with('horaire')
            ->where('agent_id', $agent->id)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->get()
            ->keyBy(function($p){ return $p->date; }); // indexés par date string

        // Construire calendrier jour par jour
        $days = [];
        $cursor = $from->copy();
        while ($cursor->lte($to)) {
            $dateStr = $cursor->toDateString();
            $expectedHoraire = $agent->groupe && $agent->groupe->horaire ? $agent->groupe->horaire : null;
            $isRestDay = false;

            if (isset($plannings[$dateStr])) {
                $pl = $plannings[$dateStr];
                $isRestDay = (bool)$pl->is_rest_day;
                if ($pl->horaire) $expectedHoraire = $pl->horaire;
            }

            $days[$dateStr] = [
                'date' => $dateStr,
                'is_rest_day' => $isRestDay,
                'expected_horaire' => $expectedHoraire,
                'presences' => collect(),
            ];

            $cursor->addDay();
        }

        // Injecter les présences
        foreach ($presences as $p) {
            $d = $p->date_reference;
            if (!$d) continue;
            if (!isset($days[$d])) {
                $days[$d] = [
                    'date' => $d,
                    'is_rest_day' => false,
                    'expected_horaire' => null,
                    'presences' => collect(),
                ];
            }
            $days[$d]['presences']->push($p);
        }

        // Grouper par mois pour le rendu
        $months = [];
        foreach ($days as $date => $day) {
            $dt = Carbon::parse($date, self::TZ);
            $m = intval($dt->month);
            if (!isset($months[$m])) {
                $months[$m] = [
                    'label' => $dt->locale('fr')->isoFormat('MMMM YYYY'),
                    'rows' => [],
                    'summary' => [
                        'planned_work_days' => 0,
                        'present_days' => 0,
                        'absences' => 0,
                        'retards' => 0,
                    ],
                ];
            }

            if ($day['is_rest_day']) {
                $months[$m]['rows'][] = [
                    'date' => $dt->format('d/m/Y'),
                    'site' => $agent->site ? ($agent->site->code . ' - ' . $agent->site->name) : '—',
                    'horaire' => $day['expected_horaire'] ? $day['expected_horaire']->libelle : '—',
                    'arrive' => 'Repos',
                    'depart' => 'Repos',
                    'retard' => '—',
                    'commentaires' => 'Jour de repos',
                ];
                continue;
            }

            if (!$day['expected_horaire']) {
                if ($day['presences']->isEmpty()) {
                    $months[$m]['rows'][] = [
                        'date' => $dt->format('d/m/Y'),
                        'site' => $agent->site ? ($agent->site->code . ' - ' . $agent->site->name) : '—',
                        'horaire' => 'Non planifié',
                        'arrive' => '—',
                        'depart' => '—',
                        'retard' => '—',
                        'commentaires' => '',
                    ];
                } else {
                    foreach ($day['presences'] as $p) {
                        $arrive = $p->started_at ? Carbon::parse($p->started_at, self::TZ)->format('H:i') : '—';
                        $depart = $p->ended_at ? Carbon::parse($p->ended_at, self::TZ)->format('H:i') : '—';
                        $retard = $p->retard ? ($p->retard === 'oui' ? 'En retard' : 'Non') : '—';
                        $months[$m]['rows'][] = [
                            'date' => $dt->format('d/m/Y'),
                            'site' => $p->site ? ($p->site->code . ' - ' . $p->site->name) : ($agent->site ? ($agent->site->code . ' - ' . $agent->site->name) : '—'),
                            'horaire' => 'Non planifié',
                            'arrive' => $arrive,
                            'depart' => $depart,
                            'retard' => $retard,
                            'commentaires' => $p->commentaires ?? '',
                        ];
                    }
                }
                continue;
            }

            $months[$m]['summary']['planned_work_days']++;

            if ($day['presences']->isEmpty()) {
                $months[$m]['rows'][] = [
                    'date' => $dt->format('d/m/Y'),
                    'site' => $agent->site ? ($agent->site->code . ' - ' . $agent->site->name) : '—',
                    'horaire' => $day['expected_horaire']->libelle . " ({$this->extractTime($day['expected_horaire']->started_at)}-{$this->extractTime($day['expected_horaire']->ended_at)})",
                    'arrive' => '—',
                    'depart' => '—',
                    'retard' => 'Absent',
                    'commentaires' => 'Aucun check-in détecté',
                ];
                $months[$m]['summary']['absences']++;
            } else {
                $first = $day['presences']->sortBy('started_at')->first();
                $last  = $day['presences']->sortByDesc('ended_at')->first();

                $arrive = $first->started_at ? Carbon::parse($first->started_at, self::TZ)->format('H:i') : '—';
                $depart = $last && $last->ended_at ? Carbon::parse($last->ended_at, self::TZ)->format('H:i') : '—';

                $expectedStart = Carbon::parse($dt->toDateString() . ' ' . $this->extractTime($day['expected_horaire']->started_at), self::TZ);
                $tolerance = $this->getToleranceMinutes($day['expected_horaire']);
                $deadline = $expectedStart->copy()->addMinutes($tolerance);

                $isRetard = false;
                $startedAtCarbon = $first->started_at ? Carbon::parse($first->started_at, self::TZ) : null;
                if ($startedAtCarbon && $startedAtCarbon->gt($deadline)) {
                    $isRetard = true;
                    $months[$m]['summary']['retards']++;
                }

                $months[$m]['rows'][] = [
                    'date' => $dt->format('d/m/Y'),
                    'site' => $first->site ? ($first->site->code . ' - ' . $first->site->name) : ($agent->site ? ($agent->site->code . ' - ' . $agent->site->name) : '—'),
                    'horaire' => $day['expected_horaire']->libelle . " ({$this->extractTime($day['expected_horaire']->started_at)}-{$this->extractTime($day['expected_horaire']->ended_at)})",
                    'arrive' => $arrive,
                    'depart' => $depart,
                    'retard' => $isRetard ? 'En retard' : 'Non',
                    'commentaires' => $first->commentaires ?? '',
                ];
                $months[$m]['summary']['present_days']++;
            }
        }

        // Assurer que tous les mois apparaissent
        $monthsOut = [];
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($months[$i])) {
                $dt = Carbon::createFromDate($from->year, $i, 1, self::TZ);
                $monthsOut[$i] = [
                    'label' => $dt->locale('fr')->isoFormat('MMMM YYYY'),
                    'rows' => [],
                    'summary' => [
                        'planned_work_days' => 0,
                        'present_days' => 0,
                        'absences' => 0,
                        'retards' => 0,
                    ],
                ];
            } else {
                $monthsOut[$i] = $months[$i];
            }
        }

        $data = [
            'agent' => $agent,
            'months' => $monthsOut,
            'periode' => [
                'from' => $from->format('d/m/Y'),
                'to'   => $to->format('d/m/Y'),
            ],
            'generatedBy' => 'Salama Plateforme',
            'generatedAt' => Carbon::now(self::TZ)->format('d/m/Y H:i'),
        ];

        $pdf = PDF::loadView('pdf.reports.agent_presence_pdf', $data)->setPaper('A4', 'landscape');
        $safeName = Str::slug($agent->fullname, '_');
        $filename = "rapport_presence_{$agent->matricule}_{$safeName}_{$from->year}.pdf";
        return $pdf->download($filename);
    }

    private function computeDateReferenceForHoraire(Carbon $day, PresenceHoraire $horaire)
    {
        $tz = self::TZ;
        $startTime = $this->extractTime($horaire->started_at);
        $endTime   = $this->extractTime($horaire->ended_at);

        $start = Carbon::parse($day->toDateString() . ' ' . $startTime, $tz);
        $end   = Carbon::parse($day->toDateString() . ' ' . $endTime, $tz);

        if ($end->lte($start)) $end->addDay(); // traversant minuit

        return $start->copy()->startOfDay();
    }

    private function extractTime(string $timeOrDatetime): string
    {
        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $timeOrDatetime)) {
            return $timeOrDatetime;
        }

        try {
            $dt = Carbon::parse($timeOrDatetime);
            return $dt->format('H:i:s');
        } catch (\Exception $e) {
            return '00:00:00';
        }
    }

    private function getToleranceMinutes(PresenceHoraire $horaire = null): int
    {
        $default = 50;
        if (!$horaire) return $default;
        if (!$horaire->tolerence) return $default;
        try {
            $t = Carbon::parse($horaire->tolerence);
            return intval($t->format('H')) * 60 + intval($t->format('i'));
        } catch (\Exception $e) {
            return $default;
        }
    }
}
