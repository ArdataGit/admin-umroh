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
        <h1>Laporan Data Pelanggan</h1>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Kode</th>
                <th>Nama Pelanggan</th>
                <th>Kontak</th>
                <th>Email</th>
                <th>Kabupaten/Kota</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelanggans as $index => $pelanggan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $pelanggan->kode_pelanggan }}</td>
                <td>{{ $pelanggan->nama_pelanggan }}</td>
                <td>{{ $pelanggan->kontak_pelanggan }}</td>
                <td>{{ $pelanggan->email_pelanggan }}</td>
                <td>{{ $pelanggan->kabupaten_kota }}</td>
                <td class="text-center">{{ $pelanggan->status_pelanggan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
