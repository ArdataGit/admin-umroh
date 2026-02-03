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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        @media print {
            @page {
                size: landscape;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>{{ $title }}</h2>
    <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Kode Paket</th>
                <th>Nama Paket</th>
                <th>Tgl Keberangkatan</th>
                <th>Jumlah Hari</th>
                <th>Maskapai</th>
                <th>Rute</th>
                <th>Harga Quad</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paketHajis as $index => $paket)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $paket->kode_paket }}</td>
                <td>{{ $paket->nama_paket }}</td>
                <td>{{ date('d-m-Y', strtotime($paket->tanggal_keberangkatan)) }}</td>
                <td>{{ $paket->jumlah_hari }} Hari</td>
                <td>{{ $paket->maskapai ? $paket->maskapai->nama_maskapai : '-' }}</td>
                <td>{{ ucfirst($paket->rute_penerbangan) }}</td>
                <td class="text-right">Rp {{ number_format($paket->harga_quad_1, 0, ',', '.') }}</td>
                <td class="text-center">{{ ucfirst($paket->status_paket) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
