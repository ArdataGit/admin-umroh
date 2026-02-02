<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Laporan Data Agent</h1>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Kode Agent</th>
                <th>Nama Agent</th>
                <th>Kontak</th>
                <th>Status</th>
                <th>Komisi Umroh</th>
                <th>Komisi Haji</th>
                <th>Kabupaten/Kota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agents as $index => $agent)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $agent->kode_agent }}</td>
                <td>{{ $agent->nama_agent }}</td>
                <td>{{ $agent->kontak_agent }}</td>
                <td class="text-center">{{ $agent->status_agent }}</td>
                <td class="text-right">Rp {{ number_format($agent->komisi_paket_umroh, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($agent->komisi_paket_haji, 0, ',', '.') }}</td>
                <td>{{ $agent->kabupaten_kota }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
