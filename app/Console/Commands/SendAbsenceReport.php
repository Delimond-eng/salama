<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Services\AbsenceReportService;
use App\Mail\AbsenceReportPerSite;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SendAbsenceReport extends Command
{
    protected $signature = 'report:absences';
    protected $description = 'Génère et envoie les rapports PDF des absences par site';

    public function handle(AbsenceReportService $service)
    {
        $packs   = $service->collectAbsences(Carbon::now('Africa/Kinshasa'));
        $now = Carbon::now()->setTimezone("Africa/Kinshasa");

        $pdf = Pdf::loadView('pdf.absence_site', [
            'packs' => $packs,
            'now'   => Carbon::now('Africa/Kinshasa'),
        ]);


        $emails = "gastondelimond@gmail.com;lionnelnawej11@gmail.com";
        $to = array_map('trim', explode(';', $emails));
        Mail::to($to)->send(new AbsenceReportPerSite( $now, $pdf->output()));

        $this->info('Rapports envoyés.');
        return Command::SUCCESS;
    }
}
