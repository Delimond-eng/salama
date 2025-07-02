<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
            text-transform: uppercase;
        }
        p {
            margin-bottom: 15px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead {
            background-color: #343a40;
            color: #ffffff;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }
        tbody tr:nth-child(odd) {
            background-color: #f2f2f2; /* effet zébré */
        }
        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>

    <h2>Rapport de présence du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h2>

    <p>Total Global : {{ $totalPresences }} / {{ $totalAgents }} agents</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Code Site</th>
                <th>Nom du Site</th>
                <th>Présences / Agents</th>
                <th>Absences</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sites as $index => $site)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $site->code }}</td>
                    <td>{{ $site->name }}</td>
                    <td>{{ $site->presences_count }} / {{ $site->agents_count }}</td>
                    <td>{{ $site->agents_count - $site->presences_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
