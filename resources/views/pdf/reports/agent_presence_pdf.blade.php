<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Rapport présence - {{ $agent->fullname }}</title>
    <style>
        @page { margin: 20mm 15mm; }
        body { font-family: 'Segoe UI', 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; line-height: 1.4; }
        
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .agent-card { display: flex; gap: 16px; align-items: center; }
        .agent-info { display: flex; flex-direction: column; justify-content: flex-start; }
        .agent-photo { width: 100px; height: 100px; border-radius: 8px; object-fit: cover; border:1px solid #ccc; }
        
        h1 { font-size: 18px; margin: 0 0 4px 0; font-weight: 700; color: #1f2937;
            font-family: 'Segoe UI', 'DejaVu Sans', sans-serif; }
        .meta { font-size: 11px; color: #4b5563; margin: 1px 0; }
        .period { font-size: 12px; color: #374151; margin-top: 4px; }
        .small { font-size: 10px; color: #6b7280; }
        
        hr { border:none; border-top:1px solid #e5e7eb; margin:12px 0 16px; }

        .month { margin-top: 16px; page-break-inside: avoid; }
        .month h3 { margin-bottom: 8px; font-size: 14px; color: #111827; font-family: 'Segoe UI', 'DejaVu Sans', sans-serif; }

        table { width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 6px; font-family: 'Segoe UI', 'DejaVu Sans', sans-serif; }
        th, td { padding: 6px 8px; text-align: left; vertical-align: top; border: 1px solid #e5e7eb; }
        th { background-color: #000; color: #fff; font-weight: 600; }
        tbody tr:nth-child(even) { background-color: #f3f4f6; }
        
        .summary { margin-top: 8px; font-weight: 600; font-size: 11px; color: #111827; font-family: 'Segoe UI', 'DejaVu Sans', sans-serif; }
        
        .footer { position: fixed; bottom: 15mm; left: 15mm; right: 15mm; text-align: right; font-size: 10px; color: #6b7280; font-family: 'Segoe UI', 'DejaVu Sans', sans-serif; }
    </style>
</head>
<body>
    <div class="header">
        <div class="agent-card">
            @php
                $photoPath = $agent->photo ? public_path('uploads/agents/'.$agent->photo) : null;
            @endphp

            @if($agent->photo && file_exists(public_path('uploads/agents/'.$agent->photo)))
                <img src="{{ public_path('uploads/agents/'.$agent->photo) }}" class="agent-photo" alt="Photo">
            @else
                <div style="width:100px;height:100px;border:1px solid #ccc;display:flex;align-items:center;justify-content:center;background:#f9fafb;color:#9ca3af;border-radius:8px;font-weight:600;">
                   <span> PHOTO</span>
                </div>
            @endif

            <div class="agent-info">
                <h1>Rapport de présence — {{ $agent->fullname }}</h1>
                <div class="meta">Matricule : <strong>{{ $agent->matricule }}</strong></div>
                <div class="meta">Site : <strong>{{ $agent->site ? $agent->site->code . ' - ' . $agent->site->name : '—' }}</strong></div>
                <div class="meta">Groupe : <strong>{{ $agent->groupe ? $agent->groupe->libelle : '—' }}</strong></div>
                <div class="period">Période : {{ $periode['from'] }} → {{ $periode['to'] }}</div>
                <div class="small">Généré par {{ $generatedBy }} — {{ $generatedAt }}</div>
            </div>

        </div>

        <div style="text-align:right;">
            <div style="font-weight:700; font-size:14px; color:#1f2937;">Salama Plateforme</div>
            <div class="small">Rapport individuel</div>
        </div>
    </div>
    <hr>

    @foreach($months as $m)
        <div class="month">
            <h3>{{ $m['label'] }}</h3>

            @if(count($m['rows']) === 0)
                <div class="small">Aucune donnée pour ce mois.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:9%;">Date</th>
                            <th style="width:22%;">Site</th>
                            <th style="width:20%;">Horaire attendu</th>
                            <th style="width:9%;">Arrivée</th>
                            <th style="width:9%;">Départ</th>
                            <th style="width:9%;">Retard</th>
                            <th style="width:22%;">Commentaires</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($m['rows'] as $row)
                            <tr>
                                <td>{{ $row['date'] }}</td>
                                <td>{{ $row['site'] }}</td>
                                <td>{{ $row['horaire'] }}</td>
                                <td>{{ $row['arrive'] }}</td>
                                <td>{{ $row['depart'] }}</td>
                                <td>{{ $row['retard'] }}</td>
                                <td>{!! nl2br(e($row['commentaires'] ?? '')) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="summary">
                Jours planifiés : {{ $m['summary']['planned_work_days'] }} — Présences : {{ $m['summary']['present_days'] }} — Absences : {{ $m['summary']['absences'] }} — Retards : {{ $m['summary']['retards'] }}
            </div>
        </div>
    @endforeach

    <div class="footer">Rapport généré : {{ $generatedAt }}</div>
</body>
</html>
