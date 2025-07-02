<?php

namespace App\Console\Commands;

use App\Models\Site;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class SendDailyPresenceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:send-daily-report';

    protected $description = 'Génère et envoie le rapport PDF de présence chaque jour à 10h';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::today();
        $yesterday = $date->copy()->subDay();

        $sites = Site::with([
            'presences' => function ($query) use ($date, $yesterday) {
                $query->whereDate('created_at', $date)
                    ->orWhereDate('created_at', $yesterday);
            },
            'presences.horaire',
            'agents',
            'secteur'
        ])->get();

        $totalPresences = 0;
        $totalAgents = 0;

        foreach ($sites as $site) {
            $site->presences = $site->presences->filter(function ($presence) use ($date) {
                $horaire = $presence->horaire;
                if (!$horaire) return false;

                try {
                    $presenceDate = Carbon::parse($presence->created_at)->startOfDay();
                    $heureDebut = Carbon::parse($horaire->started_at);
                    $heureFin = Carbon::parse($horaire->ended_at);
                } catch (\Exception $e) {
                    Log::error("⛔ Erreur parsing horaire présence : " . $e->getMessage());
                    return false;
                }

                $isHoraire24h = $heureDebut->equalTo($heureFin);

                return $isHoraire24h
                    ? $presenceDate->equalTo($date->copy()->subDay())
                    : $presenceDate->equalTo($date);
            })->values();

            $site->presences_count = $site->presences->count();
            $site->agents_count = $site->agents->count();

            $totalPresences += $site->presences_count;
            $totalAgents += $site->agents_count;
        }

        // Génération du PDF
        $pdf = Pdf::loadView('pdf.reports.presence_simple_report', [
            'sites' => $sites,
            'totalPresences' => $totalPresences,
            'totalAgents' => $totalAgents,
            'date' => $date->toDateString(),
        ]);

        $filename = 'rapport-presence-' . $date->format('Y-m-d') . '.pdf';

        // Liste des destinataires
        $emails = [
            'gastondelimond@gmail.com',
            'lionnelnawej11@gmail.com',
            'gradi.ikundomonsaba@suptech.tn'
        ];

        foreach ($emails as $email) {
            Mail::raw("Veuillez trouver ci-joint le rapport de présence du {$date->format('Y/m/d')}.", function ($message) use ($email, $pdf, $filename, $date) {
                $message->to($email)
                        ->subject("Rapport de présence du " . $date->format('d/m/Y'))
                        ->attachData($pdf->output(), $filename, [
                            'mime' => 'application/pdf',
                        ]);
            });
        }
        $this->info("✅ Rapport envoyé aux destinataires avec succès.");
        return 1;
    }

}
