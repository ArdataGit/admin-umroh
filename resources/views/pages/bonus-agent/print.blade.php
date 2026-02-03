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
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer p {
            margin: 5px 0;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Agent</th>
                <th>NIK Agent</th>
                <th>Kontak Agent</th>
                <th width="10%">Jamaah Umroh</th>
                <th width="10%">Jamaah Haji</th>
                <th width="15%">Total Bonus</th>
                <th width="15%">Sudah Dibayar</th>
                <th width="15%">Sisa Bonus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agents as $index => $agent)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $agent->nama_agent }}</td>
                    <td>{{ $agent->nik_agent }}</td>
                    <td>{{ $agent->kontak_agent }}</td>
                    <td class="text-center">{{ $agent->umroh_count }}</td>
                    <td class="text-center">{{ $agent->haji_count }}</td>
                    <td class="text-right">Rp {{ number_format($agent->total_bonus, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($agent->sudah_dibayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($agent->sisa_bonus, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <br><br><br>
        <p>Admin</p>
    </div>
</body>
</html>
